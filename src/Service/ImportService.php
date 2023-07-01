<?php

namespace App\Service;

use App\Entity\Weapon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ImportService
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

    public function import(): void
    {
        $fileName = $this->container->getParameter('base_url') . '/files/weapons.txt';
        $allWeapons = fopen($fileName, 'r+');

        while (!feof($allWeapons)) {
            $line = fgets($allWeapons);

            $weaponName = trim(str_replace('*', '', $line));
            $type = str_contains($line, '*'); // 1 - crafted, 0 - normal

            $weapon = $this->em->getRepository(Weapon::class)->findOneBy([
                'name' => $weaponName,
                'type' => $type
            ]);

            if (!$weapon) {
                $weapon = new Weapon();

                $weapon
                    ->setType($type)
                    ->setName($weaponName);

                $this->em->persist($weapon);
            }
        }

        $this->em->flush();
    }

}
