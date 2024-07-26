<?php

namespace App\Service;

use App\Entity\Draw\Draw;
use App\Entity\Draw\DrawItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Routing\RouterInterface;

class DrawStatsService
{
    private EntityManagerInterface $em;
    private Container $container;
    private RouterInterface $router;

    public function __construct(
        EntityManagerInterface $em,
        Container $container,
        RouterInterface $router,
    )
    {
        $this->em = $em;
        $this->container = $container;
        $this->router = $router;
    }

    public function getDrawStatistics(): array
    {
        $drawsCount = $this->em->getRepository(Draw::class)->count();
        $drawItems = $this->em->getRepository(DrawItem::class)->findAll();

        $drawItemsCount = [];
        $drawItemsCounter = 0;

        foreach ($drawItems as $drawItem) {
            $weapon = $drawItem->getWeapon();

            if (isset($drawItemsCount[$weapon->getId()])) {
                $drawItemsCount[$weapon->getId()]['count']++;
            } else {
                $drawItemsCount[$weapon->getId()] = [
                    'count' => 1,
                    'name' => $weapon->getName(),
                    'image' => $this->container->getParameter('base_url') . '/assets/img/weapons/' . $weapon->getImageName() . '.webp',
                    'detailsUrl' => $this->router->generate('weapon_details', ['weaponId' =>$weapon->getId()])
                ];
            }

            $drawItemsCounter++;
        }

        arsort($drawItemsCount);

        foreach ($drawItemsCount as $drawItemName => $value) {
            $drawItemsCount[$drawItemName]['percentage'] = number_format(($value['count'] / $drawItemsCounter) * 100, 2);
        }

        return [
            'items' => $drawItemsCount,
            'drawsCount' => $drawsCount,
            'drawItemsCount' => $drawItemsCounter,
        ];
    }
}
