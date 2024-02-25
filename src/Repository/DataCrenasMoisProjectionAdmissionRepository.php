<?php

namespace App\Repository;

use App\Entity\DataCrenasMoisProjectionAdmission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataCrenasMoisProjectionAdmission>
 *
 * @method DataCrenasMoisProjectionAdmission|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCrenasMoisProjectionAdmission|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCrenasMoisProjectionAdmission[]    findAll()
 * @method DataCrenasMoisProjectionAdmission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCrenasMoisProjectionAdmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataCrenasMoisProjectionAdmission::class);
    }

//    /**
//     * @return DataCrenasMoisProjectionAdmission[] Returns an array of DataCrenasMoisProjectionAdmission objects
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

//    public function findOneBySomeField($value): ?DataCrenasMoisProjectionAdmission
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
