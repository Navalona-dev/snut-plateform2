<?php

namespace App\Repository;

use App\Entity\AnneePrevisionnelle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnneePrevisionnelle>
 *
 * @method AnneePrevisionnelle|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnneePrevisionnelle|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnneePrevisionnelle[]    findAll()
 * @method AnneePrevisionnelle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnneePrevisionnelleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnneePrevisionnelle::class);
    }

    public function getAllAnnee($start = 0, $limit = 0, $search = "")
    {
        $conn = $this->_em->getConnection();

        $queryBuilder = $conn->createQueryBuilder();

        $queryBuilder->select('a.annee, a.id')
                    ->from('annee_previsionnelle', 'a');

        if (!empty($search)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('a.annee', ':search'))
                        ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->groupBy('a.id')
                    ->orderBy('a.annee', 'DESC');

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
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return AnneePrevisionnelle[] Returns an array of AnneePrevisionnelle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnneePrevisionnelle
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
