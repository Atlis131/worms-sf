<?php

namespace App\Repository;

use App\Entity\Weapon\WeaponLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WeaponLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeaponLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeaponLog[]    findAll()
 * @method WeaponLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeaponLogRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(
        ManagerRegistry        $registry,
        EntityManagerInterface $em
    )
    {
        $this->em = $em;

        parent::__construct($registry, WeaponLog::class);
    }

    public function getLogsCount($search): float|bool|int|string|null
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('count(wl.id)')
            ->from(WeaponLog::class, 'wl');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('wl.type like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        $qb = $qb
            ->getQuery();

        return $qb->getSingleScalarResult();
    }

    public function getLogsList(
        $firstRecord,
        $recordsCount,
        $order,
        $search
    )
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('wl.id as id')
            ->addSelect('wl.createdAt as createdAt')
            ->addSelect('wl.type as type')
            ->addSelect('wl.oldValue as oldValue')
            ->addSelect('wl.newValue as newValue')
            ->addSelect('u.email as username')
            ->addSelect('w.name as weaponName')
            ->addSelect('w.imageName as weaponImage')
            ->addSelect('w.id as weaponId')
            ->from(WeaponLog::class, 'wl')
            ->join('wl.user', 'u')
            ->join('wl.weapon', 'w');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('wl.type like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        $orderColumn = $order['column'] == 'username' ? 'u.' . $order['column'] : 'wl.' . $order['column'];

        $qb = $qb
            ->orderBy($orderColumn, strtoupper($order['dir']))
            ->setFirstResult($firstRecord)
            ->setMaxResults($recordsCount)
            ->getQuery();

        return $qb->getResult();

    }
}
