<?php

namespace App\Datatables;

use App\Entity\Weapon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class WeaponsDatatable
{
    private ?int $firstRecord;
    private ?string $search = null;
    private ?int $recordsCount;
    private ?array $orderColumn = null;
    private EntityManagerInterface $em;
    private Container $container;

    public function __construct(
        EntityManagerInterface $em,
        Container              $container
    )
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getDatatableData($request): array
    {
        $this->processRequest($request);

        $weaponsRepository = $this->em->getRepository(Weapon::class);

        $weaponsCount = $weaponsRepository->getWeaponsCount($this->search);
        $filteredWeaponsCount = $weaponsRepository->getWeaponsCount($this->search);
        $weapons = $weaponsRepository->getWeaponsList($this->firstRecord, $this->recordsCount, $this->orderColumn, $this->search);

        $weaponsArray = [];

        foreach ($weapons as $weapon) {
            $weaponImage = $this->container->getParameter('base_url') . '/images/weapons/' . $weapon['imageName'] . '.png';

            $weaponData['id'] = $weapon['id'];

            $weaponData['name'] = [
                'name'  => $weapon['name'],
                'image' => $weaponImage
            ];

            $weaponData['type'] = [
                'type'  => $weapon['type'],
                'id' => $weapon['id']
            ];

            $weaponData['isTool'] = [
                'isTool'  => $weapon['isTool'],
                'id' => $weapon['id']
            ];

            $weaponData['isOpenMapWeapon'] = [
                'isOpenMapWeapon'  => $weapon['isOpenMapWeapon'],
                'id' => $weapon['id']
            ];
            

            $weaponsArray[] = $weaponData;
        }

        $records = [
            'total'    => $weaponsCount,
            'filtered' => $filteredWeaponsCount,
            'data'     => $weaponsArray
        ];

        return [
            'draw'            => $request->get('draw'),
            'recordsTotal'    => $records['total'],
            'recordsFiltered' => $records['filtered'],
            'data'            => $records['data'],
        ];
    }

    private function processRequest($request): void
    {
        $this->firstRecord = $request->get('start');
        $this->recordsCount = $request->get('length');

        $columns = $request->get('columns');

        if (!is_null($request->get('order')) && isset($request->get('order')[0])) {
            $this->orderColumn = [
                'column' => $columns[$request->get('order')[0]['column']]['data'],
                'dir'    => $request->get('order')[0]['dir']
            ];
        }

        if (!is_null($request->get('search')) && $request->get('search')['value'] !== '') {
            $this->search = $request->get('search')['value'];
        }
    }
}