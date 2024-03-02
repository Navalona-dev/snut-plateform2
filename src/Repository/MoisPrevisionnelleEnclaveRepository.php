<?php

namespace App\Repository;

use App\Entity\MoisPrevisionnelleEnclave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MoisPrevisionnelleEnclave>
 *
 * @method MoisPrevisionnelleEnclave|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoisPrevisionnelleEnclave|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoisPrevisionnelleEnclave[]    findAll()
 * @method MoisPrevisionnelleEnclave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoisPrevisionnelleEnclaveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoisPrevisionnelleEnclave::class);
    }

    public function getAllMois($start = 0, $limit = 0, $search = "")
    {
        $conn = $this->_em->getConnection();

        $queryBuilder = $conn->createQueryBuilder();

        $queryBuilder->select('mp.mois, mp.id, mp.groupe_id')
                    ->from('mois_previsionnelle_enclave', 'mp');

        if (!empty($search)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('mp.mois', ':search'))
                        ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->groupBy('mp.id')
                    ->orderBy('mp.mois', 'DESC');

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
        return $this->createQueryBuilder('mp')
            ->select('COUNT(mp.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return MoisPrevisionnelleEnclave[] Returns an array of MoisPrevisionnelleEnclave objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MoisPrevisionnelleEnclave
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
