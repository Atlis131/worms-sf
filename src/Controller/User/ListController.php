<?php

namespace App\Controller\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
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
}
