<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use App\Service\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $em;
    private EmailVerifier $emailVerifier;

    public function __construct(
        EntityManagerInterface $em,
        EmailVerifier $emailVerifier
    )
    {
        $this->em = $em;
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(
        Request $request,
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
                ->setRoles(['ROLE_USER']);

            $this->em->persist($user);
            $this->em->flush();

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

    /**
     * @Route("/verify-email", name="verify_email")
     */
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        UserAuthenticatorInterface $guardHandler,
        LoginFormAuthenticator $formAuthenticator
    ): Response
    {
        $userId = $request->get('id');

        if (null === $userId) {
            return $this->redirectToRoute('register');
        }

        $user = $userRepository->findOneBy(['id'=> $userId]);

        if (null === $user) {
            return $this->redirectToRoute('register');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());

            return $this->redirectToRoute('register');
        }

        $this->addFlash('success', 'Thank you for verifying your e-mail, enjoy your stay!');

        return $guardHandler->authenticateUser(
            $user,
            $formAuthenticator,
            $request
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
