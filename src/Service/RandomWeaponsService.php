<?php

namespace App\Service;

use App\Entity\Weapon\Weapon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;

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
        Request $request
    ): array
    {
        $normalCount = $request->get('countNormal');
        $craftedCount = $request->get('countCrafted');

        $includeTools = $request->get('includeTools') == 'true';
        $includeOpenMapWeapons = $request->get('includeOpenMapWeapons') == 'true';
        $includeSentryGuns = $request->get('includeSentryGuns') == 'true';

        $randomizeCount = $request->get('randomizeCount') == 'true';
        $randomizeDelay = $request->get('randomizeDelay') == 'true';

        $allRegularWeapons = $this->getWeaponNames(
            $includeTools,
            $includeOpenMapWeapons,
            $includeSentryGuns,
            0
        );

        $regularWeapons = $this->getNormalWeapons($normalCount, $randomizeCount, $randomizeDelay, $allRegularWeapons);

        $allCraftedWeapons = $this->getWeaponNames(
            $includeTools,
            $includeOpenMapWeapons,
            $includeSentryGuns,
            1,
            $regularWeapons
        );

        $craftedWeapons = $this->getCraftedWeapons($craftedCount, $randomizeCount, $randomizeDelay, $allCraftedWeapons);

        return [
            'craftedWeapons' => $craftedWeapons,
            'normalWeapons' => $regularWeapons
        ];
    }

    private function getWeaponNames(
        bool   $includeTools,
        bool   $includeOpenMapWeapons,
        bool   $includeSentryGuns,
        string $type,
        array  $regularWeapons = null
    ): array
    {
        return $this->em->getRepository(Weapon::class)->findAllWeapons(
            $includeTools,
            $includeOpenMapWeapons,
            $includeSentryGuns,
            $type,
            $regularWeapons
        );
    }

    private function getCraftedWeapons(
        int   $craftedCount,
        bool  $randomizeCount,
        bool  $randomizeDelay,
        array $allCraftedWeapons
    ): array
    {
        $craftedWeapons = [];

        for ($i = 0; $i < $craftedCount; $i++) {
            $index = rand(0, count($allCraftedWeapons) - 1);

            $craftedWeapon = [
                'name' => $allCraftedWeapons[$index]->getName(),
                'image' => $this->container->getParameter('base_url') . '/images/weapons/' . $allCraftedWeapons[$index]->getImageName() . '.png'
            ];

            if ($randomizeCount) {
                if ($allCraftedWeapons[$index]->getIsOpenMapWeapon() || str_contains($allCraftedWeapons[$index]->getName(), 'Sentry')) {
                    $craftedWeapon['count'] = rand(1, 2);
                } else {
                    $craftedWeapon['count'] = rand(2, 6);
                }
            }

            if ($randomizeDelay) {
                if ($allCraftedWeapons[$index]->getIsOpenMapWeapon() || str_contains($allCraftedWeapons[$index]->getName(), 'Sentry')) {
                    $craftedWeapon['delay'] = rand(5, 9);
                } else {
                    $craftedWeapon['delay'] = rand(3, 6);
                }
            }

            $craftedWeapons[] = $craftedWeapon;

            unset($allCraftedWeapons[$index]);
            $allCraftedWeapons = array_values($allCraftedWeapons);
        }

        return $craftedWeapons;
    }

    private function getNormalWeapons(
        int   $normalCount,
        bool  $randomizeCount,
        bool  $randomizeDelay,
        array $allNormalWeapons
    ): array
    {
        $normalWeapons = [];

        for ($i = 0; $i < $normalCount; $i++) {
            $index = rand(0, count($allNormalWeapons) - 1);

            $normalWeapon = [
                'id' => $allNormalWeapons[$index]->getId(),
                'name' => $allNormalWeapons[$index]->getName(),
                'image' => $this->container->getParameter('base_url') . '/images/weapons/' . $allNormalWeapons[$index]->getImageName() . '.png'
            ];

            if ($randomizeCount) {
                if ($allNormalWeapons[$index]->getIsOpenMapWeapon() || str_contains($allNormalWeapons[$index]->getName(), 'Sentry')) {
                    $normalWeapon['count'] = rand(1, 2);
                } else {
                    $normalWeapon['count'] = rand(0, 9);
                }
            }

            if ($randomizeDelay) {
                if ($allNormalWeapons[$index]->getIsOpenMapWeapon() || str_contains($allNormalWeapons[$index]->getName(), 'Sentry')) {
                    $normalWeapon['delay'] = rand(3, 9);
                } else {
                    $normalWeapon['delay'] = rand(0, 2);
                }
            }

            $normalWeapons[] = $normalWeapon;

            unset($allNormalWeapons[$index]);
            $allNormalWeapons = array_values($allNormalWeapons);
        }

        return $normalWeapons;
    }
}
