<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function profile(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
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
        }

        return $this->render('pages/user/profile.html.twig', [
            'user' => $user,
            'changePasswordForm' => $userChangePasswordForm->createView()
        ]);
    }
}
