<?php

namespace App\Controller\Draw;

use App\Service\DrawStatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatsController extends AbstractController
{
    private DrawStatsService $drawStatsService;

    public function __construct(
        DrawStatsService $drawStatsService,
    )
    {
        $this->drawStatsService = $drawStatsService;
    }

    #[Route('/draw/stats', name: 'draw_stats')]
    public function list(): Response
    {
        $stats = $this->drawStatsService->getDrawStatistics();

        return $this->render('pages/draw/stats.html.twig', [
            'stats' => $stats
        ]);
    }
}
