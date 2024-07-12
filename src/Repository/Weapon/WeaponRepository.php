<?php

namespace App\Repository\Weapon;

use App\Entity\Weapon\Weapon;
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
        $includeSentryGuns,
        $includeSuperWeapons,
        $type,
        $regularWeapons
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

        if ($includeSuperWeapons) {
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

        if ($regularWeapons) {
            $weaponIds = [];

            foreach ($regularWeapons as $regularWeapon) {
                $weaponIds[] = $regularWeapon['id'];
            }

            $qb2 = $this->createQueryBuilder('wc');

            $sub = $qb2->select('w2.id')
                ->from(Weapon::class, 'w2')
                ->leftJoin('w2.baseVersion', 'wb')
                ->andWhere('wb.id in (:weapons)');

            $qb
                ->andWhere(
                    $qb->expr()->notIn('w.id',  $sub->getDQL())
                )
                ->setParameter('weapons', $weaponIds);
        }

        $qb = $qb
            ->andWhere('w.type = :type')
            ->setParameter('type', $type)
            ->getQuery();

        return $qb->getResult();
    }

    public function getWeaponsCount(
        $search,
        $filters = []
    ): float|bool|int|string|null
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
            if (isset($filters['weaponType'])) {
                $qb
                    ->andWhere("w.type = :weaponType")
                    ->setParameter('weaponType', $filters['weaponType']);
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
            ->addSelect('w.isSuperWeapon as isSuperWeapon')
            ->addSelect('w.imageName as imageName')
            ->addSelect('w.minDraw as minDraw')
            ->addSelect('w.maxDraw as maxDraw')
            ->addSelect('w.minDelay as minDelay')
            ->addSelect('w.maxDelay as maxDelay')
            ->addSelect('bv.id as baseVersionId')
            ->addSelect('bv.name as baseVersionName')
            ->addSelect('bv.imageName as baseVersionImageName')
            ->from(Weapon::class, 'w')
            ->leftJoin('w.baseVersion', 'bv');

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
