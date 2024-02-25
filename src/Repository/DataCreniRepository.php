<?php

namespace App\Repository;

use App\Entity\DataCreni;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataCreni>
 *
 * @method DataCreni|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCreni|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCreni[]    findAll()
 * @method DataCreni[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCreniRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataCreni::class);
    }

//    /**
//     * @return DataCreni[] Returns an array of DataCreni objects
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

//    public function findOneBySomeField($value): ?DataCreni
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
