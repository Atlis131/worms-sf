<?php

namespace App\Repository;

use App\Entity\Weapons;
use Doctrine\ORM\EntityRepository;

class WeaponsRepository extends EntityRepository
{
    public function findAllWeapons($includeTools, $includeOpenMapWeapons)
    {
        $qb = $this->createQueryBuilder('weapons')
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

        $qb = $qb
            ->getQuery();

        return $qb->getResult();
    }
}
