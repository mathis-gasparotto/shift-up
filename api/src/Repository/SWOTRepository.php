<?php

namespace App\Repository;

use App\Entity\SWOT;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SWOT>
 *
 * @method SWOT|null find($id, $lockMode = null, $lockVersion = null)
 * @method SWOT|null findOneBy(array $criteria, array $orderBy = null)
 * @method SWOT[]    findAll()
 * @method SWOT[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SWOTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SWOT::class);
    }

//    /**
//     * @return SWOT[] Returns an array of SWOT objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SWOT
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
