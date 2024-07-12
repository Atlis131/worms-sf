<?php

namespace App\Service;

use App\Entity\Draw\Draw;
use App\Entity\Draw\DrawItem;
use App\Entity\Weapon\Weapon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    public function createNewDrawEntry(array $weapons, Request $request): void
    {
        $user = !is_null($this->tokenStorage->getToken()) ? $this->tokenStorage->getToken()->getUser() : null;

        $draw = new Draw();
        $draw
            ->setUser($user)
            ->setIncludeOpenMapWeapons($request->get('includeOpenMapWeapons') == 'true')
            ->setIncludeSentryGuns($request->get('includeSentryGuns') == 'true')
            ->setIncludeTools($request->get('includeTools') == 'true')
            ->setIncludeSuperWeapons($request->get('includeSuperWeapons') == 'true')
            ->setRandomWeaponsCount($request->get('randomizeCount') == 'true')
            ->setRandomWeaponsDelay($request->get('randomizeDelay') == 'true')
            ->setCreatedAt(new DateTime('now'));

        $this->em->persist($draw);

        foreach ($weapons as $weaponType) {
            foreach ($weaponType as $weaponData) {
                $weapon = $this->em->getRepository(Weapon::class)->findOneBy([
                    'id' => $weaponData['id']
                ]);

                $drawItem = new DrawItem();
                $drawItem
                    ->setDraw($draw)
                    ->setWeapon($weapon);

                if (isset($weaponData['count'])) {
                    $drawItem->setCount($weaponData['count']);
                }

                if (isset($weaponData['delay'])) {
                    $drawItem->setCount($weaponData['delay']);
                }

                $this->em->persist($drawItem);
            }
        }

        $this->em->flush();
    }
}
