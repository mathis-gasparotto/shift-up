<?php

namespace App\Repository;

use App\Entity\STP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<STP>
 *
 * @method STP|null find($id, $lockMode = null, $lockVersion = null)
 * @method STP|null findOneBy(array $criteria, array $orderBy = null)
 * @method STP[]    findAll()
 * @method STP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class STPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, STP::class);
    }

//    /**
//     * @return STP[] Returns an array of STP objects
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

//    public function findOneBySomeField($value): ?STP
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
