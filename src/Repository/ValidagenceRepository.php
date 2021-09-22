<?php

namespace App\Repository;

use App\Entity\Validagence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Validagence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Validagence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Validagence[]    findAll()
 * @method Validagence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValidagenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Validagence::class);
    }

    // /**
    //  * @return Validagence[] Returns an array of Validagence objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Validagence
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
