<?php

namespace App\Repository;

use App\Entity\MarketingMix5;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MarketingMix5>
 *
 * @method MarketingMix5|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarketingMix5|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarketingMix5[]    findAll()
 * @method MarketingMix5[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketingMix5Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarketingMix5::class);
    }

//    /**
//     * @return MarketingMix5[] Returns an array of MarketingMix5 objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MarketingMix5
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
