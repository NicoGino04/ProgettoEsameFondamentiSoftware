<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

        /**
         * @return Activity[] Returns an array of Activity objects
         */
    public function findByDateField($user, $data = new \DateTime()): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.data = :giorno')
            ->setParameter('giorno', $data->format('Y-m-d'))
//                ->andWhere('p.pasto = :val')
//                ->setParameter('val', "colazione")
            ->andWhere('p.user= :user')
            ->setParameter('user', $user->getId())
            ->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    //    public function findOneBySomeField($value): ?Activity
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
