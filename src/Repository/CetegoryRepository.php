<?php

namespace App\Repository;

use App\Entity\Cetegory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cetegory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cetegory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cetegory[]    findAll()
 * @method Cetegory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CetegoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cetegory::class);
    }

    // /**
    //  * @return Cetegory[] Returns an array of Cetegory objects
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
    public function findOneBySomeField($value): ?Cetegory
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
