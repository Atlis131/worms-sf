<?php

namespace App\Listener;

use App\Entity\Weapon;
use App\Entity\WeaponLog;
use DateTimeImmutable;
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

    public function postUpdate(Weapon $weapon, LifecycleEventArgs $args)
    {
        $entityManager = $args->getObjectManager();
        $entity = $args->getObject();
        $user = $this->tokenStorage->getToken()->getUser();
        $changes = $entityManager->getUnitOfWork()->getEntityChangeSet($entity);

        foreach ($changes as $key => $value) {
            $weaponLog = new WeaponLog();
            $weaponLog
                ->setType($key)
                ->setUser($user)
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setNewValue(is_bool($value[1]) ? (int) $value[1] : $value[1])
                ->setOldValue(is_bool($value[0]) ? (int) $value[0] : $value[0]);

            $entityManager->persist($weaponLog);
        }

        $entityManager->flush();
    }
}