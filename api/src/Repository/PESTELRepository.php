<?php

namespace App\Repository;

use App\Entity\PESTEL;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PESTEL>
 *
 * @method PESTEL|null find($id, $lockMode = null, $lockVersion = null)
 * @method PESTEL|null findOneBy(array $criteria, array $orderBy = null)
 * @method PESTEL[]    findAll()
 * @method PESTEL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PESTELRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PESTEL::class);
    }

//    /**
//     * @return PESTEL[] Returns an array of PESTEL objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PESTEL
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
