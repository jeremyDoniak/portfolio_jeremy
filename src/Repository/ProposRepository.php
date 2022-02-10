<?php

namespace App\Repository;

use App\Entity\Propos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Propos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Propos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Propos[]    findAll()
 * @method Propos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProposRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propos::class);
    }

    // /**
    //  * @return Propos[] Returns an array of Propos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Propos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
