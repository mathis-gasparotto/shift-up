<?php

namespace App\Repository;

use App\Entity\BuyerPersona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BuyerPersona>
 *
 * @method BuyerPersona|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuyerPersona|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuyerPersona[]    findAll()
 * @method BuyerPersona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuyerPersonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuyerPersona::class);
    }

//    /**
//     * @return BuyerPersona[] Returns an array of BuyerPersona objects
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

//    public function findOneBySomeField($value): ?BuyerPersona
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
