<?php

namespace App\Service;

use App\Entity\Weapon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class RandomWeaponsService
{
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

    public function getRandomWeapons(
        $request
    ): array
    {
        $normalCount = $request->get('countNormal');
        $craftedCount = $request->get('countCrafted');
        $includeTools = $request->get('includeTools') == 'true';
        $includeOpenMapWeapons = $request->get('includeOpenMapWeapons') == 'true';
        $includeSentryGuns = $request->get('includeSentryGuns') == 'true';
        $randomizeCount = $request->get('randomizeCount') == 'true';
        $randomizeDelay = $request->get('randomizeDelay') == 'true';

        $craftedWeapons = $normalWeapons = [];

        $weapons = $this->getWeaponNames(
            $includeTools,
            $includeOpenMapWeapons,
            $includeSentryGuns
        );

        for ($i = 0; $i < $craftedCount; $i++) {
            $index = rand(0, count($weapons['craftedWeapons']) - 1);

            $craftedWeapon = [
                'name'  => $weapons['craftedWeapons'][$index]->getName(),
                'image' => $this->container->getParameter('base_url') . '/images/weapons/' . $weapons['craftedWeapons'][$index]->getImageName() . '.png'
            ];

            if ($randomizeCount) {
                if ($weapons['craftedWeapons'][$index]->getIsOpenMapWeapon() || str_contains($weapons['craftedWeapons'][$index]->getName(), 'Sentry')) {
                    $craftedWeapon['count'] = rand(1, 2);
                } else {
                    $craftedWeapon['count'] = rand(2, 6);
                }
            }

            if ($randomizeDelay) {
                if ($weapons['craftedWeapons'][$index]->getIsOpenMapWeapon() || str_contains($weapons['craftedWeapons'][$index]->getName(), 'Sentry')) {
                    $craftedWeapon['delay'] = rand(5, 9);
                } else {
                    $craftedWeapon['delay'] = rand(3, 6);
                }
            }

            $craftedWeapons[] = $craftedWeapon;

            unset($weapons['craftedWeapons'][$index]);
            $weapons['craftedWeapons'] = array_values($weapons['craftedWeapons']);
        }

        for ($i = 0; $i < $normalCount; $i++) {
            $index = rand(0, count($weapons['normalWeapons']) - 1);

            $normalWeapon = [
                'name'  => $weapons['normalWeapons'][$index]->getName(),
                'image' => $this->container->getParameter('base_url') . '/images/weapons/' . $weapons['normalWeapons'][$index]->getImageName() . '.png'
            ];

            if ($randomizeCount) {
                if ($weapons['normalWeapons'][$index]->getIsOpenMapWeapon() || str_contains($weapons['normalWeapons'][$index]->getName(), 'Sentry')) {
                    $normalWeapon['count'] = rand(1, 2);
                } else {
                    $normalWeapon['count'] = rand(0, 9);
                }
            }

            if ($randomizeDelay) {
                if ($weapons['normalWeapons'][$index]->getIsOpenMapWeapon() || str_contains($weapons['normalWeapons'][$index]->getName(), 'Sentry')) {
                    $normalWeapon['delay'] = rand(3, 9);
                } else {
                    $normalWeapon['delay'] = rand(0, 2);
                }
            }

            $normalWeapons[] = $normalWeapon;

            unset($weapons['normalWeapons'][$index]);
            $weapons['normalWeapons'] = array_values($weapons['normalWeapons']);
        }

        return [
            'craftedWeapons' => $craftedWeapons,
            'normalWeapons'  => $normalWeapons
        ];
    }

    private function getWeaponNames(
        $includeTools,
        $includeOpenMapWeapons,
        $includeSentryGuns
    ): array
    {
        $allWeapons = $this->em->getRepository(Weapon::class)->findAllWeapons(
            $includeTools,
            $includeOpenMapWeapons,
            $includeSentryGuns
        );

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
            'normalWeapons'  => $normalWeapons
        ];
    }
}
