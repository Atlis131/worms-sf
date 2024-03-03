<?php

namespace App\Service;

use App\Entity\Draw\Draw;
use App\Entity\Draw\DrawItem;
use App\Entity\Weapon\Weapon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DrawHistoryService
{
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface  $tokenStorage
    )
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function createNewDrawEntry(array $weapons): void
    {
        $user = !is_null($this->tokenStorage->getToken()) ? $this->tokenStorage->getToken()->getUser() : null;

        $draw = new Draw();
        $draw
            ->setUser($user)
            ->setCreatedAt(new DateTime('now'));

        $this->em->persist($draw);

        foreach ($weapons as $weaponType) {
            foreach ($weaponType as $weapon) {
                $weapon = $this->em->getRepository(Weapon::class)->findOneBy([
                    'id' => $weapon['id']
                ]);

                $drawItem = new DrawItem();
                $drawItem
                    ->setDraw($draw)
                    ->setWeapon($weapon);

                $this->em->persist($drawItem);
            }
        }

        $this->em->flush();
    }
}
