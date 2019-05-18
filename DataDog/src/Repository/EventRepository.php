<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /*
     * @return Event[] Returns newest events by array
     */
    public  function findNewest(array $requestParameters){

        $qb = $this->createQueryBuilder('e');

        if($requestParameters)
        {
            $values = $requestParameters['filter_event'];
            if($values['title'])
            {
                $qb
                    ->andWhere('e.title LIKE :searchTitle')
                    ->setParameter('searchTitle', '%'.$values['title'].'%');
            }
            if($values['category'])
            {
                //->where(e.eventCategories.)
                $qb
                    ->innerJoin('e.eventCategories', 'c')
                    ->andWhere('c.id = :category')
                    ->setParameter('category', $values['category']);
            }
            if($values['earliestDate'])
            {
                $qb
                    ->andWhere('e.date > :earlDate')
                    ->setParameter('earlDate', $values['earliestDate']);
            }
            if($values['latestDate'])
            {
                $qb
                    ->andWhere('e.date <= :latDate')
                    ->setParameter('latDate', $values['latestDate']);
            }
            if($values['maxPrice'])
            {
                $qb
                    ->andWhere('e.price <= :price')
                    ->setParameter('price', $values['maxPrice']*100);
            }

        }

        return $qb
            ->orderBy('e.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
