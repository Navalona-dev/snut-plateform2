<?php

namespace App\Repository;

use App\Entity\DataCrenas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataCrenas>
 *
 * @method DataCrenas|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCrenas|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCrenas[]    findAll()
 * @method DataCrenas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCrenasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataCrenas::class);
    }

//    /**
//     * @return DataCrenas[] Returns an array of DataCrenas objects
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

//    public function findOneBySomeField($value): ?DataCrenas
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
