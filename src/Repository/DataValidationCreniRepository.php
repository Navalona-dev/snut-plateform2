<?php

namespace App\Repository;

use App\Entity\DataValidationCreni;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataValidationCreni>
 *
 * @method DataValidationCreni|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataValidationCreni|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataValidationCreni[]    findAll()
 * @method DataValidationCreni[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataValidationCreniRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataValidationCreni::class);
    }

//    /**
//     * @return DataValidationCreni[] Returns an array of DataValidationCreni objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DataValidationCreni
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
