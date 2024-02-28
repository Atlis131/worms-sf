<?php

namespace App\Controller\Security;

use App\Entity\User\User;
use App\Form\UserChangePasswordType;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\EmailVerifier;
use App\Service\UserLogService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $em;
    private EmailVerifier          $emailVerifier;
    private EmailService           $emailService;
    private UserLogService         $userLogService;
    private UserRepository         $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        EmailVerifier          $emailVerifier,
        EmailService           $emailService,
        UserLogService         $userLogService,
        UserRepository         $userRepository,
    )
    {
        $this->em = $em;
        $this->emailVerifier = $emailVerifier;
        $this->emailService = $emailService;
        $this->userLogService = $userLogService;
        $this->userRepository = $userRepository;
    }

    #[Route('/login', name: 'login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/login.html.twig', [
            'error' => $error,
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userModel = $form->getData();

            $user = new User();
            $user
                ->setEmail($userModel->email)
                ->setEmailCanonical($userModel->email)
                ->setUsernameCanonical($userModel->email)
                ->setUsername($userModel->email)
                ->setPassword($userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('password')->getData()
                ))
                ->setName($userModel->name)
                ->setRegistrationDate(new DateTime('now'))
                ->setRoles(['ROLE_USER']);

            $this->em->persist($user);
            $this->em->flush();

            $this->userLogService->addToLog($user, 'Created account', 'Security');

            $this->emailVerifier->sendEmailConfirmation('verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address($this->getParameter('email')['username'], $this->getParameter('email')['name']))
                    ->to($user->getEmail())
                    ->subject('[Worms Toolkit] Activate your account')
                    ->htmlTemplate('email/confirmation.html.twig')
            );

            $this->addFlash('success', 'Successfully registered! Check your e-mail for verification link');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify-email', name: 'verify_email')]
    public function verifyUserEmail(
        Request                    $request,
        UserAuthenticatorInterface $guardHandler,
        LoginFormAuthenticator     $formAuthenticator
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $userId = $request->get('id');

        if (null === $userId) {
            return $this->redirectToRoute('register');
        }

        $user = $this->userRepository->findOneBy(['id' => $userId]);

        if (null === $user) {
            return $this->redirectToRoute('register');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());

            $this->userLogService->addToLog($user, 'Failed to verify ' . substr($exception->getReason(), 0, 128) . '...', 'Security');

            return $this->redirectToRoute('register');
        }

        $this->addFlash('success', 'Thank you for verifying your e-mail, enjoy your stay!');

        $this->userLogService->addToLog($user, 'Verified account', 'Security');

        return $guardHandler->authenticateUser(
            $user,
            $formAuthenticator,
            $request
        );
    }

    #[Route('/reset-password', name: 'reset_password')]
    public function resetPassword(Request $request): RedirectResponse|Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        if ($request->isMethod('POST')) {
            $user = $this->em->getRepository(User::class)->findOneBy([
                'email' => $request->get('email')
            ]);

            if ($user) {
                if (!$user->getEmailVerificationDate()) {
                    $this->addFlash('error', 'Your account needs to be activated first!');
                    return $this->redirectToRoute('homepage');
                }

                $currDate = new DateTime('now');
                $passwordResetToken = md5($user->getEmail() . $currDate->format('Y-m-d H:i:s') . $user->getName());

                $user
                    ->setResetPasswordToken($passwordResetToken)
                    ->setResetPasswordTokenTime($currDate->modify('+ 30 minutes'));

                $this->em->persist($user);
                $this->em->flush();

                $this->sendPasswordResetEmail($user);
            }

            $this->addFlash('success', 'Check your email for instructions how to reset your password');

            $this->userLogService->addToLog($user, 'Requested password reset', 'Security');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/reset_password.html.twig',);
    }

    #[Route('/set-password/{passwordHash}', name: 'set_password')]
    public function setNewPassword(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        UserAuthenticatorInterface  $guardHandler,
        LoginFormAuthenticator      $formAuthenticator,
        string                      $passwordHash
    ): RedirectResponse|Response
    {
        $user = $this->em->getRepository(User::class)->findOneBy([
            'resetPasswordToken' => $passwordHash
        ]);

        if (!$user) {
            $this->addFlash('error', "Something went wrong! Couldn't find user based on provided passwordHash or passwordHash expired");

            return $this->redirectToRoute('homepage');
        } else {
            if ($user->getResetPasswordTokenTime() < new DateTime('now') || is_null($user->getResetPasswordToken())) {
                $this->addFlash('error', "Something went wrong! Couldn't find user based on provided passwordHash or passwordHash expired");

                $user
                    ->setResetPasswordToken(null)
                    ->setResetPasswordTokenTime(null);

                $this->em->persist($user);
                $this->em->flush();

                $this->userLogService->addToLog($user, 'Requested password reset - invalid / expired token', 'Security');

                return $this->redirectToRoute('login');
            }

            $form = $this->createForm(UserChangePasswordType::class, null, [
                'resetPassword' => true
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user
                    ->setPassword($userPasswordHasherInterface->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    ))
                    ->setResetPasswordToken(null)
                    ->setResetPasswordTokenTime(null);

                $this->em->persist($user);
                $this->em->flush();

                $this->addFlash('success', "Successfully changed password! You've been automatically logged in!");

                $this->userLogService->addToLog($user, 'Successfully changed password via email', 'Security');

                return $guardHandler->authenticateUser(
                    $user,
                    $formAuthenticator,
                    $request
                );
            }

            return $this->render('security/set_new_password.html.twig', [
                'form' => $form->createView()
            ]);

        }
    }

    private function sendPasswordResetEmail(
        User $user
    ): void
    {
        $url = $this->generateUrl('set_password', [
            'passwordHash' => $user->getResetPasswordToken()
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $body = $this->renderView('email/password_reset.html.twig', [
            'signedUrl' => $url,
            'user'      => $user
        ]);

        $this->emailService->sendEmail('[Worms Toolkit] Change your password', $body, trim($user->getEmail()));
    }

    #[Route('/logout}', name: 'logout')]
    public function logout()
    {

    }
}
