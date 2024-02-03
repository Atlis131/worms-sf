<?php

namespace App\Repository;

use App\Entity\UserLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLog[]    findAll()
 * @method UserLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLogRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(
        ManagerRegistry        $registry,
        EntityManagerInterface $em
    )
    {
        $this->em = $em;

        parent::__construct($registry, UserLog::class);
    }

    public function getLogsCount($search): float|bool|int|string|null
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('count(ul.id)')
            ->from(UserLog::class, 'ul');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('ul.message like :phrase')
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
            ->select('ul.id as id')
            ->addSelect('ul.createdAt as createdAt')
            ->addSelect('ul.type as type')
            ->addSelect('ul.message as message')
            ->addSelect('u.email as username')
            ->from(UserLog::class, 'ul')
            ->join('ul.user', 'u');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('ul.message like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        $orderColumn = $order['column'] == 'username' ? 'u.' . $order['column'] : 'ul.' . $order['column'];

        $qb = $qb
            ->orderBy($orderColumn, strtoupper($order['dir']))
            ->setFirstResult($firstRecord)
            ->setMaxResults($recordsCount)
            ->getQuery();

        return $qb->getResult();

    }
}
