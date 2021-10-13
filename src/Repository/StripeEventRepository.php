<?php

namespace App\Repository;

use App\Entity\StripeEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StripeEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method StripeEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method StripeEvent[]    findAll()
 * @method StripeEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StripeEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StripeEvent::class);
    }

    // /**
    //  * @return StripeEvent[] Returns an array of StripeEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StripeEvent
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
