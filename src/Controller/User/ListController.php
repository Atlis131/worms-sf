<?php

namespace App\Controller\User;

use App\Datatables\UserDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    private UserDatatable $userDatatable;

    public function __construct(
        UserDatatable $userDatatable,
    )
    {
        $this->userDatatable = $userDatatable;
    }

    #[Route('/user/list', name: 'user_list')]
    public function index(): Response
    {
        return $this->render('pages/user/list.html.twig');
    }

    #[Route('/user/list/data', name: 'user_list_data', methods: ['POST'])]
    public function listData(Request $request): JsonResponse
    {
        $response = $this->userDatatable->getDatatableData($request);

        return new JsonResponse($response, 200);
    }
}
