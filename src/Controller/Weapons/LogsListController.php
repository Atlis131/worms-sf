<?php

namespace App\Controller\Weapons;

use App\Datatables\WeaponLogDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogsListController extends AbstractController
{
    private WeaponLogDatatable $weaponLogDatatable;

    public function __construct(
        WeaponLogDatatable $weaponLogDatatable,
    )
    {
        $this->weaponLogDatatable = $weaponLogDatatable;
    }

    #[Route('/weapons/logs', name: 'weapons_logs')]
    public function list(): Response
    {
        return $this->render('pages/weapons/log.html.twig');
    }

    #[Route('/weapons/logs/data', name: 'weapons_logs_data', methods: ["POST"])]
    public function listData(Request $request): JsonResponse
    {
        $response = $this->weaponLogDatatable->getDatatableData($request);

        return new JsonResponse($response, 200);
    }
}
