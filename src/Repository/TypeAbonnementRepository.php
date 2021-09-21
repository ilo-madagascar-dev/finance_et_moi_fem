<?php

namespace App\Repository;

use App\Entity\TypeAbonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeAbonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeAbonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeAbonnement[]    findAll()
 * @method TypeAbonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeAbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeAbonnement::class);
    }

    // /**
    //  * @return TypeAbonnement[] Returns an array of TypeAbonnement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeAbonnement
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
