<?php

namespace App\Repository;

use App\Entity\DataValidationCrenas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataValidationCrenas>
 *
 * @method DataValidationCrenas|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataValidationCrenas|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataValidationCrenas[]    findAll()
 * @method DataValidationCrenas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataValidationCrenasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataValidationCrenas::class);
    }

//    /**
//     * @return DataValidationCrenas[] Returns an array of DataValidationCrenas objects
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

//    public function findOneBySomeField($value): ?DataValidationCrenas
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
