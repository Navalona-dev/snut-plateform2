<?php

namespace App\Repository;

use App\Entity\QuantitiesProductRiskExpiry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuantitiesProductRiskExpiry>
 *
 * @method QuantitiesProductRiskExpiry|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantitiesProductRiskExpiry|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantitiesProductRiskExpiry[]    findAll()
 * @method QuantitiesProductRiskExpiry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantitiesProductRiskExpiryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantitiesProductRiskExpiry::class);
    }

//    /**
//     * @return QuantitiesProductRiskExpiry[] Returns an array of QuantitiesProductRiskExpiry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuantitiesProductRiskExpiry
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
