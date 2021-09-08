<?php

namespace App\Repository;

use App\Entity\MoyenPaiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoyenPaiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoyenPaiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoyenPaiement[]    findAll()
 * @method MoyenPaiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoyenPaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoyenPaiement::class);
    }

    // /**
    //  * @return MoyenPaiement[] Returns an array of MoyenPaiement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MoyenPaiement
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
