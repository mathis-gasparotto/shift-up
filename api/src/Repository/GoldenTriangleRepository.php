<?php

namespace App\Repository;

use App\Entity\GoldenTriangle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GoldenTriangle>
 *
 * @method GoldenTriangle|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoldenTriangle|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoldenTriangle[]    findAll()
 * @method GoldenTriangle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoldenTriangleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoldenTriangle::class);
    }

//    /**
//     * @return GoldenTriangle[] Returns an array of GoldenTriangle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GoldenTriangle
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
