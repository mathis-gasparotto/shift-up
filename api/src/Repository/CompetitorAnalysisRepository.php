<?php

namespace App\Repository;

use App\Entity\CompetiorAnalysis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompetiorAnalysis>
 *
 * @method CompetiorAnalysis|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetiorAnalysis|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetiorAnalysis[]    findAll()
 * @method CompetiorAnalysis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitorAnalysisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetiorAnalysis::class);
    }

    //    /**
    //     * @return CompetiorAnalysis[] Returns an array of CompetiorAnalysis objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CompetiorAnalysis
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
