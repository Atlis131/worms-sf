<?php

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByUsernameOrEmail(
        $value
    ): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val or u.username = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUsersCount($search): float|bool|int|string|null
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('count(u.id)')
            ->from(User::class, 'u');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('u.username like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        $qb = $qb
            ->getQuery();

        return $qb->getSingleScalarResult();
    }

    public function getUsersList(
        $firstRecord,
        $recordsCount,
        $order,
        $search
    )
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('u.id as id')
            ->addSelect('u.email as email')
            ->addSelect('u.username as username')
            ->addSelect('u.name as name')
            ->addSelect('u.roles as roles')
            ->from(User::class, 'u');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('u.username like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        $orderColumn = 'u.' . $order['column'];

        $qb = $qb
            ->orderBy($orderColumn, strtoupper($order['dir']))
            ->setFirstResult($firstRecord)
            ->setMaxResults($recordsCount)
            ->getQuery();

        return $qb->getResult();

    }
}
