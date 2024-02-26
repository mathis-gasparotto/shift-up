<?php

namespace App\Repository;

use App\Entity\MarketingMix4;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MarketingMix4>
 *
 * @method MarketingMix4|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarketingMix4|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarketingMix4[]    findAll()
 * @method MarketingMix4[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketingMix4Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarketingMix4::class);
    }

//    /**
//     * @return MarketingMix4[] Returns an array of MarketingMix4 objects
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

//    public function findOneBySomeField($value): ?MarketingMix4
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
