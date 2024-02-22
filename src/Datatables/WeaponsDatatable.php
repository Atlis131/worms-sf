<?php

namespace App\Datatables;

use App\Entity\Weapon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Routing\RouterInterface;

class WeaponsDatatable extends Datatable
{
    private Container $container;
    private RouterInterface $router;

    public function __construct(
        EntityManagerInterface $em,
        Container              $container,
        RouterInterface        $router
    )
    {
        parent::__construct($em);
        $this->container = $container;
        $this->router = $router;
    }

    public function getDatatableData($request): array
    {
        $this->processRequest($request);

        $weaponsRepository = $this->em->getRepository(Weapon::class);

        $weaponsCount = $weaponsRepository->getWeaponsCount($this->search);
        $filteredWeaponsCount = $weaponsRepository->getWeaponsCount($this->search, $this->filters);
        $weapons = $weaponsRepository->getWeaponsList($this->firstRecord, $this->recordsCount, $this->orderColumn, $this->search, $this->filters);

        $weaponsArray = [];

        foreach ($weapons as $weapon) {
            $weaponData['id'] = $weapon['id'];

            $weaponData['name'] = [
                'name' => $weapon['name'],
                'image' => $this->container->getParameter('base_url') . '/images/weapons/' . $weapon['imageName'] . '.png'
            ];

            $weaponData['type'] = [
                'type' => $weapon['type'],
                'id' => $weapon['id']
            ];

            $weaponData['isTool'] = [
                'isTool' => $weapon['isTool'],
                'id' => $weapon['id']
            ];

            $weaponData['isOpenMapWeapon'] = [
                'isOpenMapWeapon' => $weapon['isOpenMapWeapon'],
                'id' => $weapon['id']
            ];

            if (isset($weapon['baseVersionId'])) {
                $weaponData['baseVersion'] = [
                    'name' => $weapon['baseVersionName'],
                    'image' => $this->container->getParameter('base_url') . '/images/weapons/' . $weapon['baseVersionImageName'] . '.png',
                    'id' => $weapon['baseVersionId']
                ];
            } else {
                if ($weapon['type'] == 1) {
                    $weaponData['baseVersion'] = [
                        'name' => 'Undefined',
                        'image' => $this->container->getParameter('base_url') . '/assets/img/undefined.webp',
                    ];
                } else {
                    $weaponData['baseVersion'] = [
                        'name' => 'Base weapon already.',
                        'image' => $this->container->getParameter('base_url') . '/assets/img/undefined.webp',
                    ];
                }
            }

            $weaponData['actions'] = [
                'edit' => $this->router->generate('weapons_edit', ['weaponId' => $weapon['id']])
            ];

            $weaponsArray[] = $weaponData;
        }

        $records = [
            'total' => $weaponsCount,
            'filtered' => $filteredWeaponsCount,
            'data' => $weaponsArray
        ];

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $records['total'],
            'recordsFiltered' => $records['filtered'],
            'data' => $records['data'],
        ];
    }
}
