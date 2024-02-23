<?php

namespace App\Controller\Weapons;

use App\Entity\Weapon;
use App\Form\WeaponsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
    )
    {
        $this->em = $em;
    }

    #[Route('/weapons/edit/{weaponId}', name: 'weapons_edit')]
    public function edit(
        int     $weaponId,
        Request $request): Response
    {
        $weapon = $this->em->getRepository(Weapon::class)->findOneBy([
            'id' => $weaponId
        ]);

        $form = $this->createForm(WeaponsType::class, $weapon);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($weapon);
                $this->em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('success', 'Successfully changed weapon');

                return $this->redirectToRoute('weapons_list');
            }
        }

        return $this->render('pages/weapons/edit.html.twig', [
            'form' => $form->createView(),
            'weaponType' => $weapon->getType()
        ]);
    }
}
