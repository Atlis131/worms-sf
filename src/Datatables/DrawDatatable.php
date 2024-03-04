<?php

namespace App\Datatables;

use App\Entity\Draw\Draw;
use App\Entity\Draw\DrawItem;
use App\Entity\Weapon\WeaponLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Routing\RouterInterface;

class DrawDatatable extends Datatable
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

        $weaponLogs = $this->em->getRepository(Draw::class);

        $drawCount = $weaponLogs->getDrawsCount($this->search);
        $filteredDrawCount = $weaponLogs->getDrawsCount($this->search);
        $draws = $weaponLogs->getDrawsList($this->firstRecord, $this->recordsCount, $this->orderColumn, $this->search);

        $drawsArray = [];

        foreach ($draws as $draw) {
            $drawData = [];

            $formattedData = $draw['createdAt']->format('Y-m-d H:i:s');

            $drawData['id'] = $draw['id'];
            $drawData['username'] = is_null($draw['username']) ? 'Anonymous' : $draw['username'];

            $drawData['weapons'] = $this->getDrawDataWeapons($draw['id']);

            $drawData['createdAt'] = $formattedData;

            $drawsArray[] = $drawData;
        }

        $records = [
            'total' => $drawCount,
            'filtered' => $filteredDrawCount,
            'data' => $drawsArray
        ];

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $records['total'],
            'recordsFiltered' => $records['filtered'],
            'data' => $records['data'],
        ];
    }

    private function getDrawDataWeapons(int $drawId)
    {
        $drawWeapons = $this->em->getRepository(DrawItem::class)->findBy([
            'draw' => $drawId
        ]);

        $weapons = '';

        foreach ($drawWeapons as $drawWeapon) {
            if ($drawWeapon->getWeapon()->getType() == 0) {
                $weapons .= '<div>' . $drawWeapon->getWeapon()->getName() . '</div>';
            } else {
                $weapons .= '<div>' . $drawWeapon->getWeapon()->getName() . '</div>';
            }

        }

        return $weapons;
    }
}
