<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
    )
    {
        $this->em = $em;
    }

    /**
     * @Route("/user/list", name="user_list")
     */
    public function index(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();

        return $this->render('pages/user/list.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/edit/{userId}", name="user_edit")
     */
    public function edit($userId, Request $request): Response
    {
        $user = $this->em->getRepository(User::class)->findOneBy([
            'id' => $userId
        ]);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($user);
                $this->em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('success', 'Succesfully changed user');

                return $this->redirectToRoute('user_list');
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}