<?php

namespace App\Controller\Weapons;

use App\Service\RandomWeaponsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomController extends AbstractController
{
    private RandomWeaponsService $randomWeaponsService;

    public function __construct(
        RandomWeaponsService   $randomWeaponsService,
    )
    {
        $this->randomWeaponsService = $randomWeaponsService;
    }

    /**
     * @Route("/weapons/draw", name="weapons_random")
     */
    public function drawWeapons(): Response
    {
        return $this->render('pages/weapons/random.html.twig');
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
