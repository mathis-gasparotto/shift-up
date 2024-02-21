<?php

namespace App\Repository;

use App\Entity\BusinessModelCanvas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BusinessModelCanvas>
 *
 * @method BusinessModelCanvas|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessModelCanvas|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessModelCanvas[]    findAll()
 * @method BusinessModelCanvas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessModelCanvasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BusinessModelCanvas::class);
    }

//    /**
//     * @return BusinessModelCanvas[] Returns an array of BusinessModelCanvas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BusinessModelCanvas
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
