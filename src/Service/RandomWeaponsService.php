<?php

namespace App\Service;

use App\Entity\Weapons;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class RandomWeaponsService
{
    private EntityManagerInterface $em;
    private Container $container;

    public function __construct(
        EntityManagerInterface $em,
        Container $container
    )
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getRandomWeapons($request): array
    {
        $normalCount = $request->get('countNormal');
        $craftedCount = $request->get('countCrafted');
        $includeTools = $request->get('includeTools');
        $includeOpenMapWeapons = $request->get('includeOpenMapWeapons');

        $craftedWeapons = [];
        $normalWeapons = [];

        $weapons = $this->getWeaponNames($includeTools, $includeOpenMapWeapons);

        for ($i = 0; $i < $craftedCount; $i++) {
            $index = rand(0, count($weapons['craftedWeapons']) - 1);

            $craftedWeapons[] = [
                'name' => $weapons['craftedWeapons'][$index]->getName(),
                'image' => $this->container->getParameter('base_url') . '/images/weapons/' . $weapons['craftedWeapons'][$index]->getImageName() . '.png'
            ];

            unset($weapons['craftedWeapons'][$index]);
            $weapons['craftedWeapons'] = array_values($weapons['craftedWeapons']);
        }

        for ($i = 0; $i < $normalCount; $i++) {
            $index = rand(0, count($weapons['normalWeapons']) - 1);

            $normalWeapons[] = [
                'name' => $weapons['normalWeapons'][$index]->getName(),
                'image' => $this->container->getParameter('base_url') . '/images/weapons/' . $weapons['normalWeapons'][$index]->getImageName() . '.png'
            ];
            unset($weapons['normalWeapons'][$index]);
            $weapons['normalWeapons'] = array_values($weapons['normalWeapons']);
        }

        return [
            'craftedWeapons' => $craftedWeapons,
            'normalWeapons' => $normalWeapons
        ];
    }

    private function getWeaponNames($includeTools, $includeOpenMapWeapons): array
    {
        $allWeapons = $this->em->getRepository(Weapons::class)->findAllWeapons($includeTools == 'true', $includeOpenMapWeapons == 'true');

        $normalWeapons = [];
        $craftedWeapons = [];

        foreach ($allWeapons as $weapon) {
            if ($weapon->getType() == 1) {
                $craftedWeapons[] = $weapon;
            } else {
                $normalWeapons[] = $weapon;
            }
        }

        return [
            'craftedWeapons' => $craftedWeapons,
            'normalWeapons' => $normalWeapons
        ];
    }

}