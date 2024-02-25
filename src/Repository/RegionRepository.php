<?php

namespace App\Repository;

use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Region>
 *
 * @method Region|null find($id, $lockMode = null, $lockVersion = null)
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    public function getAllRegion($start = 0, $limit = 0, $search = "")
    {
        $conn = $this->_em->getConnection();

        $queryBuilder = $conn->createQueryBuilder();

        $queryBuilder->select('r.nom, r.id')
                    ->from('region', 'r');

        if (!empty($search)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('r.nom', ':search'))
                        ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->groupBy('r.id')
                    ->orderBy('r.nom', 'DESC');

        if (is_int($limit) && $limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        if (is_int($start) && $start > 0) {
            $queryBuilder->setFirstResult($start);
        }

        $statement = $queryBuilder->execute();

        return $statement->fetchAllAssociative(); // 
       
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
//    /**
//     * @return Region[] Returns an array of Region objects
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

//    public function findOneBySomeField($value): ?Region
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
