<?php

namespace App\Repository;

use App\Entity\Weapons;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class WeaponsRepository extends EntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        $this->em = $em;

        parent::__construct($em, $class);
    }

    public function findAllWeapons($includeTools, $includeOpenMapWeapons, $includeSentryGuns)
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('w')
            ->from(Weapons::class, 'w');

        if ($includeTools) {
            $qb = $qb
                ->andWhere('w.isTool in (0,1)');
        } else {
            $qb = $qb
                ->andWhere('w.isTool = 0');
        }

        if ($includeOpenMapWeapons) {
            $qb = $qb
                ->andWhere('w.isOpenMapWeapon in (0,1)');
        } else {
            $qb = $qb
                ->andWhere('w.isOpenMapWeapon = 0');
        }

        if (!$includeSentryGuns) {
            $qb = $qb
                ->andWhere('w.name not like :phrase')
                ->setParameter('phrase', '%' . 'sentry' . '%');
        }

        $qb = $qb
            ->getQuery();

        return $qb->getResult();
    }

    public function getWeaponsCount()
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('count(w.id)')
            ->from(Weapons::class, 'w');

        $qb = $qb
            ->getQuery();

        return $qb->getSingleScalarResult();
    }

    public function getWeaponsList($firstRecord, $recordsCount, $order)
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('w.id as id')
            ->addSelect('w.name as name')
            ->addSelect('w.isTool as isTool')
            ->addSelect('w.type as type')
            ->addSelect('w.isOpenMapWeapon as isOpenMapWeapon')
            ->addSelect('w.imageName as imageName')
            ->from(Weapons::class, 'w');

        $qb = $qb
            ->orderBy('w.' . $order['column'], strtoupper($order['dir']))
            ->setFirstResult($firstRecord)
            ->setMaxResults($recordsCount)
            ->getQuery();

        return $qb->getResult();

    }
}
