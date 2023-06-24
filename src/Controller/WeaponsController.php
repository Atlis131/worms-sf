<?php

namespace App\Controller;

use App\Entity\Weapons;
use App\Form\WeaponsType;
use App\Service\RandomWeaponsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeaponsController extends AbstractController
{
    private RandomWeaponsService $randomWeaponsService;
    private EntityManagerInterface $em;

    public function __construct(
        RandomWeaponsService   $randomWeaponsService,
        EntityManagerInterface $em
    )
    {
        $this->randomWeaponsService = $randomWeaponsService;
        $this->em = $em;
    }

    /**
     * @Route("/weapons", name="weapons_random")
     */
    public function index(): Response
    {
        return $this->render('pages/weapons/random.html.twig');
    }

    /**
     * @Route("/weapons/list", name="weapons_list")
     */
    public function list(): Response
    {
        $weapons = $this->em->getRepository(Weapons::class)->findAll();

        return $this->render('pages/weapons/list.html.twig', [
            'weapons' => $weapons
        ]);
    }

    /**
     * @Route("/weapons/edit/{weaponId}", name="weapons_edit")
     */
    public function edit($weaponId, Request $request): Response
    {
        $weapon = $this->em->getRepository(Weapons::class)->findOneBy([
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
                    ->add('success', 'Poprawnie zaktualizowano broń');

                return $this->redirectToRoute('weapons_list');
            }
        }

        return $this->render('pages/weapons/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/weapons/random", name="weapons_random_get", options={"expose"=true})
     */
    public function getRandomWeapons(Request $request): JsonResponse
    {
        $weapons = $this->randomWeaponsService->getRandomWeapons($request);

        return new JsonResponse($weapons);
    }
}
