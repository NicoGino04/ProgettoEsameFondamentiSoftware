<?php

namespace App\Repository;

use App\Entity\Pasto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pasto>
 */
class PastoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pasto::class);
    }

        /**
         * @return Pasto[] Returns an array of Pasto objects
         */
        public function findByDateField($user, $data = new \DateTime()): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.giorno = :giorno')
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

    //    public function findOneBySomeField($value): ?Pasto
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
