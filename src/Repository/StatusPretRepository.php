<?php

namespace App\Repository;

use App\Entity\StatusPret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatusPret|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusPret|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusPret[]    findAll()
 * @method StatusPret[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusPretRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatusPret::class);
    }

    // /**
    //  * @return StatusPret[] Returns an array of StatusPret objects
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
    public function findOneBySomeField($value): ?StatusPret
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
