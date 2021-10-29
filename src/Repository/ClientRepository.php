<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\SubscriptionSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function findAllClientsResearched(SubscriptionSearch $search)
    {
        $query = $this->createQueryBuilder('c');
        
        //Tarina jointures
        if ($search->getSubscription()) {
            $query = $query->join('c.abonnement', 'ca', Join::WITH, $query->expr()->eq('ca.typeAbonnement', ':typeAbonnement'));
            $query = $query->setParameter(':typeAbonnement', $search->getSubscription());
        }

        if ($search->getTown()) {
            $query->andWhere('c.town LIKE :town')
            ->setParameter('town', '%' . $search->getTown() .'%');
        }

        if ($search->getPostalCode()) {
            $query->andWhere('c.postalCode LIKE :postalCode')
            ->setParameter('postalCode', '%' . $search->getPostalCode() . '%');
        }
        
        $query = $query->getQuery()->getResult();

        return $query;
    }

    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
