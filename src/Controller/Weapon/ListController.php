<?php

namespace App\Controller\Weapon;

use App\Datatables\WeaponsDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    private WeaponsDatatable $weaponsDatatable;

    public function __construct(
        WeaponsDatatable $weaponsDatatable,
    )
    {
        $this->weaponsDatatable = $weaponsDatatable;
    }

    #[Route('/weapon/list', name: 'weapon_list')]
    public function list(): Response
    {
        return $this->render('pages/weapons/list.html.twig');
    }

    #[Route('/weapon/list/data', name: 'weapon_list_data', methods: ["POST"])]
    public function listData(Request $request): JsonResponse
    {
        $response = $this->weaponsDatatable->getDatatableData($request);

        return new JsonResponse($response, 200);
    }
}
