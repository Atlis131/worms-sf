<?php

namespace App\Datatables;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class UserDatatable extends Datatable
{
    private RouterInterface $router;

    public function __construct(
        EntityManagerInterface $em,
        RouterInterface $router
    )
    {
        $this->router = $router;
        parent::__construct($em);
    }

    public function getDatatableData($request): array
    {
        $this->processRequest($request);

        $usersRepository = $this->em->getRepository(User::class);

        $count = $usersRepository->getUsersCount($this->search);
        $filteredCount = $usersRepository->getUsersCount($this->search);
        $users = $usersRepository->getUsersList($this->firstRecord, $this->recordsCount, $this->orderColumn, $this->search);

        $usersArray = [];

        foreach ($users as $user) {
            $userData = [];

            $formattedRoles = $this->getFormattedRoles($user);

            $userData['id'] = $user['id'];
            $userData['username'] = $user['username'];
            $userData['email'] = $user['email'];
            $userData['name'] = $user['name'];
            $userData['roles'] = $formattedRoles;

            $userData['actions'] = [
                'edit' => $this->router->generate('user_edit', ['userId' => $user['id']]),
                'impersonate' => $this->router->generate('homepage', ['_switch_user' => $user['username']])
            ];

            $usersArray[] = $userData;
        }

        $records = [
            'total'    => $count,
            'filtered' => $filteredCount,
            'data'     => $usersArray
        ];

        return [
            'draw'            => $request->get('draw'),
            'recordsTotal'    => $records['total'],
            'recordsFiltered' => $records['filtered'],
            'data'            => $records['data'],
        ];
    }

    private function getFormattedRoles(array $user): string
    {
        $rolesString = '';

        foreach ($user['roles'] as $role) {
            $rolesString .= '<span class="btn btn-primary">' . $role . '</span>';

        }

        return $rolesString;
    }
}
