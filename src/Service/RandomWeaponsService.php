<?php

namespace App\Service;

use App\Entity\Weapons;
use Doctrine\ORM\EntityManagerInterface;

class RandomWeaponsService
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
    )
    {
        $this->em = $em;
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

            $craftedWeapons[] = $weapons['craftedWeapons'][$index];
            unset($weapons['craftedWeapons'][$index]);
            $weapons['craftedWeapons'] = array_values($weapons['craftedWeapons']);
        }

        for ($i = 0; $i < $normalCount; $i++) {
            $index = rand(0, count($weapons['normalWeapons']) - 1);

            $normalWeapons[] = $weapons['normalWeapons'][$index];
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
                $craftedWeapons[] = $weapon->getName();
            } else {
                $normalWeapons[] = $weapon->getName();
            }
        }

        return [
            'craftedWeapons' => $craftedWeapons,
            'normalWeapons' => $normalWeapons
        ];
    }

}