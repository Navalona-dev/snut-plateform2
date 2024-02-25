<?php

namespace App\Repository;

use App\Entity\Pvrd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pvrd>
 *
 * @method Pvrd|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pvrd|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pvrd[]    findAll()
 * @method Pvrd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PvrdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pvrd::class);
    }

//    /**
//     * @return Pvrd[] Returns an array of Pvrd objects
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

//    public function findOneBySomeField($value): ?Pvrd
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
