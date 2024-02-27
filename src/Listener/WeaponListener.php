<?php

namespace App\Listener;

use App\Entity\Weapon\Weapon;
use App\Entity\Weapon\WeaponLog;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Weapon::class)]
class WeaponListener
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function postUpdate(Weapon $weapon, LifecycleEventArgs $args): void
    {
        $entityManager = $args->getObjectManager();
        $entity = $args->getObject();
        $user = $this->tokenStorage->getToken()->getUser();
        $changes = $entityManager->getUnitOfWork()->getEntityChangeSet($entity);

        foreach ($changes as $key => $value) {
            if ($value[1] instanceof Weapon) {
                $value[1] = $value[1]->getName();
            }

            if ($value[0] instanceof Weapon) {
                $value[0] = $value[0]->getName();
            }

            if (is_null($value[0])) {
                $value[0] = '-';
            }

            if (is_null($value[1])) {
                $value[1] = '-';
            }

            $weaponLog = new WeaponLog();
            $weaponLog
                ->setType($key)
                ->setUser($user)
                ->setWeapon($weapon)
                ->setCreatedAt(new DateTime('now'))
                ->setNewValue(is_bool($value[1]) ? (int) $value[1] : $value[1])
                ->setOldValue(is_bool($value[0]) ? (int) $value[0] : $value[0]);

            $entityManager->persist($weaponLog);
        }

        if ($weapon->getType() == 0) {
            $weapon->setBaseVersion(null);

            $entityManager->persist($weapon);
        }

        $entityManager->flush();
    }
}