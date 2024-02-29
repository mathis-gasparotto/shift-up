<?php

namespace App\Repository;

use App\Entity\CompetitorAnalysis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompetitorAnalysis>
 *
 * @method CompetitorAnalysis|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetitorAnalysis|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetitorAnalysis[]    findAll()
 * @method CompetitorAnalysis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitorAnalysisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetitorAnalysis::class);
    }

    //    /**
    //     * @return CompetitorAnalysis[] Returns an array of CompetitorAnalysis objects
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

    //    public function findOneBySomeField($value): ?CompetitorAnalysis
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
