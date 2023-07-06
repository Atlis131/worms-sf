<?php

namespace App\Controller\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function profile(): Response
    {
        /* @var User $user */
        $user = $this->getUser();

        return $this->render('pages/user/profile.html.twig', [
            'user' => $user
        ]);
    }
}
