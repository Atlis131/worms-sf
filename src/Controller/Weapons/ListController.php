<?php

namespace App\Controller\Weapons;

use App\Datatables\WeaponsDatatable;
use App\Entity\Weapons;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    private WeaponsDatatable $weaponsDatatable;

    public function __construct(
        WeaponsDatatable       $weaponsDatatable,
    )
    {
        $this->weaponsDatatable = $weaponsDatatable;
    }


    /**
     * @Route("/weapons/list", name="weapons_list")
     */
    public function list(): Response
    {
        return $this->render('pages/weapons/list.html.twig');
    }

    /**
     * @Route("/weapons/list/data", name="weapons_list_data", methods={"POST"})
     */
    public function listData(Request $request): JsonResponse
    {
        $response = $this->weaponsDatatable->getDatatableData($request);

        return new JsonResponse($response, 200);
    }
}
