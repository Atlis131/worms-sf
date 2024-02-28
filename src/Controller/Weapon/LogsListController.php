<?php

namespace App\Controller\Weapon;

use App\Datatables\WeaponLogDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LogsListController extends AbstractController
{
    private WeaponLogDatatable $weaponLogDatatable;

    public function __construct(
        WeaponLogDatatable $weaponLogDatatable,
    )
    {
        $this->weaponLogDatatable = $weaponLogDatatable;
    }

    #[Route('/weapon/logs', name: 'weapon_logs')]
    public function list(): Response
    {
        return $this->render('pages/weapons/log.html.twig');
    }

    #[Route('/weapon/logs/data', name: 'weapon_logs_data', methods: ["POST"])]
    public function listData(Request $request): JsonResponse
    {
        $response = $this->weaponLogDatatable->getDatatableData($request);

        return new JsonResponse($response, 200);
    }
}
