<?php

namespace App\Repository;

use App\Entity\RmaNut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RmaNut>
 *
 * @method RmaNut|null find($id, $lockMode = null, $lockVersion = null)
 * @method RmaNut|null findOneBy(array $criteria, array $orderBy = null)
 * @method RmaNut[]    findAll()
 * @method RmaNut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RmaNutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RmaNut::class);
    }

    public function getById(int $id): ?RmaNut
    {
        return $this->find($id);
    }

//    /**
//     * @return RmaNut[] Returns an array of RmaNut objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RmaNut
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
