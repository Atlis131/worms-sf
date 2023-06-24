<?php

namespace App\Repository;

use App\Entity\Weapons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Weapons>
 *
 * @method Weapons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weapons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weapons[]    findAll()
 * @method Weapons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeaponsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weapons::class);
    }

    public function findAllWeapons(bool $includeTools, $includeOpenMapWeapons)
    {
        $qb = $this->createQueryBuilder('weapons')
            ->select('w')
            ->from(Weapons::class, 'w');

        if ($includeTools) {
            $qb = $qb
                ->andWhere('w.isTool in (0,1)');
        } else {
            $qb = $qb
                ->andWhere('w.isTool = 0') ;
        }

        if ($includeOpenMapWeapons) {
            $qb = $qb
                ->andWhere('w.isOpenMapWeapon in (0,1)');
        } else {
            $qb = $qb
                ->andWhere('w.isOpenMapWeapon = 0') ;
        }

        $qb = $qb
            ->getQuery();

        return $qb->getResult();
    }
}
