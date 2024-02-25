<?php

namespace App\Repository;

use App\Entity\CentreRecuperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CentreRecuperation>
 *
 * @method CentreRecuperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CentreRecuperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CentreRecuperation[]    findAll()
 * @method CentreRecuperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentreRecuperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CentreRecuperation::class);
    }

    public function getAllCentre($start = 0, $limit = 0, $search = "")
    {
        $conn = $this->_em->getConnection();

        $queryBuilder = $conn->createQueryBuilder();

        $queryBuilder->select('c.nom, c.id')
                    ->from('centre_recuperation', 'c');

        if (!empty($search)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('c.nom', ':search'))
                        ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->groupBy('c.id')
                    ->orderBy('c.nom', 'DESC');

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
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
//    /**
//     * @return CentreRecuperation[] Returns an array of CentreRecuperation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CentreRecuperation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
