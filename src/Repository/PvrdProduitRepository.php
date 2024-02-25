<?php

namespace App\Repository;

use App\Entity\PvrdProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PvrdProduit>
 *
 * @method PvrdProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method PvrdProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method PvrdProduit[]    findAll()
 * @method PvrdProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PvrdProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PvrdProduit::class);
    }

//    /**
//     * @return PvrdProduit[] Returns an array of PvrdProduit objects
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

//    public function findOneBySomeField($value): ?PvrdProduit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
