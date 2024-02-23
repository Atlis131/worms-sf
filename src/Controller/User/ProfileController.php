<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserChangePasswordType;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private UserLogService         $userLogService;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserLogService         $userLogService
    )
    {
        $this->entityManager = $entityManager;
        $this->userLogService = $userLogService;
    }

    #[Route('/user/profile', name: 'user_profile')]
    public function profile(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ): Response
    {
        /* @var User $user */
        $user = $this->getUser();

        $userChangePasswordForm = $this->createForm(UserChangePasswordType::class, $user);
        $userChangePasswordForm->handleRequest($request);

        if ($request->isMethod('POST') && $userChangePasswordForm->isSubmitted() && $userChangePasswordForm->isValid()) {
            $user->setPassword($userPasswordHasherInterface->hashPassword(
                $user,
                $userChangePasswordForm->get('password')->getData()
            ));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Successfully changed your password');

            $this->userLogService->addToLog($user, 'Successfully changed password via profile', 'Security');
        }

        return $this->render('pages/user/profile.html.twig', [
            'user'               => $user,
            'changePasswordForm' => $userChangePasswordForm->createView()
        ]);
    }
}
