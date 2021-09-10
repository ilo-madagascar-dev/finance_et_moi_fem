<?php

namespace App\Repository;

use App\Entity\SousCompte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SousCompte|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousCompte|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousCompte[]    findAll()
 * @method SousCompte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousCompteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousCompte::class);
    }

    // /**
    //  * @return SousCompte[] Returns an array of SousCompte objects
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
    public function findOneBySomeField($value): ?SousCompte
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
