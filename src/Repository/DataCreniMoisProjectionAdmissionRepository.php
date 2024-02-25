<?php

namespace App\Repository;

use App\Entity\DataCreniMoisProjectionAdmission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataCreniMoisProjectionAdmission>
 *
 * @method DataCreniMoisProjectionAdmission|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCreniMoisProjectionAdmission|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCreniMoisProjectionAdmission[]    findAll()
 * @method DataCreniMoisProjectionAdmission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCreniMoisProjectionAdmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataCreniMoisProjectionAdmission::class);
    }

//    /**
//     * @return DataCreniMoisProjectionAdmission[] Returns an array of DataCreniMoisProjectionAdmission objects
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

//    public function findOneBySomeField($value): ?DataCreniMoisProjectionAdmission
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
