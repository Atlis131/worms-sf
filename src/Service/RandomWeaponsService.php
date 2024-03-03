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
            Weapon::WEAPON_TYPE_REGULAR
        );

        $regularWeapons = $this->getNormalWeapons($normalCount, $randomizeCount, $randomizeDelay, $allRegularWeapons);

        $allCraftedWeapons = $this->getWeaponNames(
            $includeTools,
            $includeOpenMapWeapons,
            $includeSentryGuns,
            Weapon::WEAPON_TYPE_CRAFTED,
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
                'id' => $allCraftedWeapons[$index]->getId(),
                'name' => $allCraftedWeapons[$index]->getName(),
                'image' => $this->container->getParameter('base_url') . '/assets/img/weapons/' . $allCraftedWeapons[$index]->getImageName() . '.webp'
            ];

            if ($randomizeCount) {
                $count = rand($allCraftedWeapons[$index]->getMin(), $allCraftedWeapons[$index]->getMax());
                $craftedWeapon['count'] = $count;
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
                'image' => $this->container->getParameter('base_url') . '/assets/img/weapons/' . $allNormalWeapons[$index]->getImageName() . '.webp'
            ];

            if ($randomizeCount) {
                $count = rand($allNormalWeapons[$index]->getMin(), $allNormalWeapons[$index]->getMax());
                $normalWeapon['count'] = $count;
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
