<?php

namespace App\Repository;

use App\Entity\CreniMoisProjectionsAdmissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CreniMoisProjectionsAdmissions>
 *
 * @method CreniMoisProjectionsAdmissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreniMoisProjectionsAdmissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreniMoisProjectionsAdmissions[]    findAll()
 * @method CreniMoisProjectionsAdmissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreniMoisProjectionsAdmissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreniMoisProjectionsAdmissions::class);
    }

    public function getAllMoisProjectionSemestrielle($start = 0, $limit = 0, $search = "")
    {
        $queryBuilder = $this->createQueryBuilder('mp');
        $queryBuilder = $queryBuilder->leftJoin('mp.CommandeSemestrielle', 'c');
        $queryBuilder->select('c.Nom, mp.id');
                    

        if (!empty($search)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('c.Nom', ':search'))
                        ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->groupBy('mp.id')
                    ->orderBy('c.Nom', 'DESC');

        if (is_int($limit) && $limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        if (is_int($start) && $start > 0) {
            $queryBuilder->setFirstResult($start);
        }

       // $statement = $queryBuilder->execute();

        return $queryBuilder->getQuery()->getArrayResult(); // 
       
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('mp')
            ->select('COUNT(mp.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return CreniMoisProjectionsAdmissions[] Returns an array of CreniMoisProjectionsAdmissions objects
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

//    public function findOneBySomeField($value): ?CreniMoisProjectionsAdmissions
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
