<?php

namespace App\Repository\Draw;

use App\Entity\Draw\DrawItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DrawItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrawItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrawItem[]    findAll()
 * @method DrawItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrawItemRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(
        ManagerRegistry        $registry,
        EntityManagerInterface $em
    )
    {
        $this->em = $em;

        parent::__construct($registry, DrawItem::class);
    }
}
