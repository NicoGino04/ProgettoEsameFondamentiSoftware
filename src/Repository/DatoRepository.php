<?php

namespace App\Repository;

use App\Entity\Dato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dato>
 */
class DatoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dato::class);
    }

    public function getGoalsGroupedByDate($user): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.user= :user')
            ->setParameter('user', $user->getId())
            ->select('g.data AS data, SUM(g.quantita) AS totale')
            ->groupBy('g.data')
            ->orderBy('g.data', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    //    /**
    //     * @return Dato[] Returns an array of Dato objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Dato
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
