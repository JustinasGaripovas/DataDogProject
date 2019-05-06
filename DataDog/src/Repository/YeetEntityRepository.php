<?php

namespace App\Repository;

use App\Entity\YeetEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method YeetEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method YeetEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method YeetEntity[]    findAll()
 * @method YeetEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YeetEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, YeetEntity::class);
    }

    // /**
    //  * @return YeetEntity[] Returns an array of YeetEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?YeetEntity
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
