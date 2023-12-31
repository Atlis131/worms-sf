<?php

namespace App\Repository;

use App\Entity\Weapon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class WeaponRepository extends EntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
        ClassMetadata          $class
    )
    {
        $this->em = $em;

        parent::__construct($em, $class);
    }

    public function findAllWeapons(
        $includeTools,
        $includeOpenMapWeapons,
        $includeSentryGuns
    )
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('w')
            ->from(Weapon::class, 'w');

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

    public function getWeaponsCount(
        $search,
        $filters = []
    )
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('count(w.id)')
            ->from(Weapon::class, 'w');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('w.name like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        if (!empty($filters)) {
            if (isset($filters['weaponType'])) {
                $qb
                    ->andWhere("w.type = :weaponType")
                    ->setParameter('weaponType', $filters['weaponType']);
            }
            if (isset($filters['tool'])) {
                $qb
                    ->andWhere("w.isTool = :tool")
                    ->setParameter('tool', $filters['tool']);
            }
            if (isset($filters['openMap'])) {
                $qb
                    ->andWhere("w.isOpenMapWeapon = :openMap")
                    ->setParameter('openMap', $filters['openMap']);
            }
        }

        $qb = $qb
            ->getQuery();

        return $qb->getSingleScalarResult();
    }

    public function getWeaponsList(
        $firstRecord,
        $recordsCount,
        $order,
        $search,
        $filters = []
    )
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('w.id as id')
            ->addSelect('w.name as name')
            ->addSelect('w.isTool as isTool')
            ->addSelect('w.type as type')
            ->addSelect('w.isOpenMapWeapon as isOpenMapWeapon')
            ->addSelect('w.imageName as imageName')
            ->from(Weapon::class, 'w');

        if (!empty($filters)) {
            if (isset($filters['weaponType'])) {
                $qb
                    ->andWhere("w.type = :weaponType")
                    ->setParameter('weaponType', $filters['weaponType']);
            }
            if (isset($filters['tool'])) {
                $qb
                    ->andWhere("w.isTool = :tool")
                    ->setParameter('tool', $filters['tool']);
            }
            if (isset($filters['openMap'])) {
                $qb
                    ->andWhere("w.isOpenMapWeapon = :openMap")
                    ->setParameter('openMap', $filters['openMap']);
            }
        }

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('w.name like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        $qb = $qb
            ->orderBy('w.' . $order['column'], strtoupper($order['dir']))
            ->setFirstResult($firstRecord)
            ->setMaxResults($recordsCount)
            ->getQuery();

        return $qb->getResult();

    }
}
