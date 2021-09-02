<?php

namespace App\Repository;

use App\Entity\Abonnememnt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Abonnememnt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnememnt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnememnt[]    findAll()
 * @method Abonnememnt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnememntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnememnt::class);
    }

    // /**
    //  * @return Abonnememnt[] Returns an array of Abonnememnt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Abonnememnt
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
