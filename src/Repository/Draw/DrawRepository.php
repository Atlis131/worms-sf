<?php

namespace App\Repository\Draw;

use App\Entity\Draw\Draw;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Draw|null find($id, $lockMode = null, $lockVersion = null)
 * @method Draw|null findOneBy(array $criteria, array $orderBy = null)
 * @method Draw[]    findAll()
 * @method Draw[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrawRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(
        ManagerRegistry        $registry,
        EntityManagerInterface $em
    )
    {
        $this->em = $em;

        parent::__construct($registry, Draw::class);
    }

    public function getDrawsCount($search): float|bool|int|string|null
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('count(d.id)')
            ->from(Draw::class, 'd')
            ->leftJoin('d.user', 'u');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('u.username like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        $qb = $qb
            ->getQuery();

        return $qb->getSingleScalarResult();
    }

    public function getDrawsList(
        $firstRecord,
        $recordsCount,
        $order,
        $search
    )
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('d.id')
            ->addSelect('d.createdAt as createdAt')
            ->addSelect('u.username as username')
            ->addSelect('d.includeOpenMapWeapons as includeOpenMapWeapons')
            ->addSelect('d.includeSentryGuns as includeSentryGuns')
            ->addSelect('d.includeSuperWeapons as includeSuperWeapons')
            ->addSelect('d.includeTools as includeTools')
            ->addSelect('d.randomWeaponsCount as randomWeaponsCount')
            ->addSelect('d.randomWeaponsDelay as randomWeaponsDelay')
            ->from(Draw::class, 'd')
            ->leftJoin('d.user','u');

        if (!is_null($search)) {
            $qb = $qb
                ->andWhere('u.name like :phrase')
                ->setParameter('phrase', '%' . $search . '%');
        }

        $orderColumn = $order['column'] == 'username' ? 'u.' . $order['column'] : 'd.' . $order['column'];

        $qb = $qb
            ->orderBy($orderColumn, strtoupper($order['dir']))
            ->setFirstResult($firstRecord)
            ->setMaxResults($recordsCount)
            ->getQuery();

        return $qb->getResult();

    }
}
