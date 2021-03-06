<?php
namespace App\Repository;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function findAll()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByResetToken($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.reset_token= :reset_token')
            ->setParameter('reset_token', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByEmail($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}