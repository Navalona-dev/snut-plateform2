<?php

namespace App\Repository;

use App\Entity\TypeCentreRecuperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeCentreRecuperation>
 *
 * @method TypeCentreRecuperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCentreRecuperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCentreRecuperation[]    findAll()
 * @method TypeCentreRecuperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCentreRecuperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCentreRecuperation::class);
    }

//    /**
//     * @return TypeCentreRecuperation[] Returns an array of TypeCentreRecuperation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeCentreRecuperation
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
