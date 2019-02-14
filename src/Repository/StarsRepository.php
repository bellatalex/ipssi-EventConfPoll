<?php

namespace App\Repository;

use App\Entity\Stars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Stars|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stars|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stars[]    findAll()
 * @method Stars[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StarsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Stars::class);
    }

    // /**
    //  * @return Stars[] Returns an array of Stars objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stars
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
