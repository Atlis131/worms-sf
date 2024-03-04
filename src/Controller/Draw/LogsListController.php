<?php

namespace App\Controller\Draw;

use App\Datatables\DrawDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LogsListController extends AbstractController
{
    private DrawDatatable $drawDatatable;

    public function __construct(
        DrawDatatable $drawDatatable,
    )
    {
        $this->drawDatatable = $drawDatatable;
    }

    #[Route('/draw/logs', name: 'draw_logs')]
    public function list(): Response
    {
        return $this->render('pages/draw/log.html.twig');
    }

    #[Route('/draw/logs/data', name: 'draw_logs_data', methods: ["POST"])]
    public function listData(Request $request): JsonResponse
    {
        $response = $this->drawDatatable->getDatatableData($request);

        return new JsonResponse($response, 200);
    }
}
