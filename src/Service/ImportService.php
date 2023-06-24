<?php

namespace App\Service;

use App\Entity\Weapons;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ImportService
{
    private EntityManagerInterface $em;
    private KernelInterface $kernel;

    public function __construct(
        EntityManagerInterface $em,
        KernelInterface        $kernel
    )
    {
        $this->em = $em;
        $this->kernel = $kernel;
    }

    public function import(): void
    {
        $fileName = $this->kernel->getProjectDir() . '\public\files\weapons.txt';
        $allWeapons = fopen($fileName, 'r+');

        while (!feof($allWeapons)) {
            $line = fgets($allWeapons);

            $weaponName = str_replace('*', '', $line);
            $weaponName = trim($weaponName);
            $type = str_contains($line, '*'); // 1 - crafted, 0 - normal

            $weapon = $this->em->getRepository(Weapons::class)->findOneBy([
                'name' => $weaponName,
                'type' => $type
            ]);

            if (!$weapon) {
                $weapon = new Weapons();

                $weapon
                    ->setType($type)
                    ->setName($weaponName);

                $this->em->persist($weapon);
            }
        }

        $this->em->flush();
    }

}