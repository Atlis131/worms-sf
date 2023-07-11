<?php

namespace App\Controller\User;

use App\Datatables\UserLogDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogsListController extends AbstractController
{
    private UserLogDatatable $userLogDatatable;

    public function __construct(
        UserLogDatatable $userLogDatatable,
    )
    {
        $this->userLogDatatable = $userLogDatatable;
    }


    /**
     * @Route("/user/logs", name="user_logs")
     */
    public function list(): Response
    {
        return $this->render('pages/user/log.html.twig');
    }

    /**
     * @Route("/user/logs/data", name="user_logs_data", methods={"POST"})
     */
    public function listData(Request $request): JsonResponse
    {
        $response = $this->userLogDatatable->getDatatableData($request);

        return new JsonResponse($response, 200);
    }
}
