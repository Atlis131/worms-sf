<?php

namespace App\Controller\Weapon;

use App\Service\DrawHistoryService;
use App\Service\RandomWeaponsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RandomController extends AbstractController
{
    private RandomWeaponsService $randomWeaponsService;
    private DrawHistoryService $drawHistoryService;

    public function __construct(
        RandomWeaponsService $randomWeaponsService,
        DrawHistoryService $drawHistoryService
    )
    {
        $this->randomWeaponsService = $randomWeaponsService;
        $this->drawHistoryService = $drawHistoryService;
    }
    
    #[Route('/weapon/draw', name: 'weapon_draw')]
    public function drawWeapons(): Response
    {
        return $this->render('pages/weapons/random.html.twig');
    }

    #[Route('/weapon/random', name: 'weapon_random', options: ["expose" =>  true])]
    public function getRandomWeapons(Request $request): JsonResponse
    {
        $weapons = $this->randomWeaponsService->getRandomWeapons($request);

        $this->drawHistoryService->createNewDrawEntry($weapons);

        return new JsonResponse($weapons);
    }
}
