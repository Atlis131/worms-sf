<?php

namespace App\Controller\Weapon;

use App\Entity\Weapon\Weapon;
use App\Form\WeaponsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetailsController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
    )
    {
        $this->em = $em;
    }

    #[Route('/weapon/details/{weaponId}', name: 'weapon_details')]
    public function edit(int $weaponId): Response
    {
        $weapon = $this->em->getRepository(Weapon::class)->findOneBy([
            'id' => $weaponId
        ]);

        return $this->render('pages/weapons/details.html.twig', [
            'weapon' => $weapon
        ]);
    }
}
