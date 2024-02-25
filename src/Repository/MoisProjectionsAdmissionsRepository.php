<?php

namespace App\Repository;

use App\Entity\MoisProjectionsAdmissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MoisProjectionsAdmissions>
 *
 * @method MoisProjectionsAdmissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoisProjectionsAdmissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoisProjectionsAdmissions[]    findAll()
 * @method MoisProjectionsAdmissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoisProjectionsAdmissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoisProjectionsAdmissions::class);
    }

    public function getAllMoisProjectionTrimestrielle($start = 0, $limit = 0, $search = "")
    {
        //$conn = $this->_em->getConnection();

        $queryBuilder = $this->createQueryBuilder('mp');
        $queryBuilder->leftJoin('mp.Groupe', 'g');
        $queryBuilder->leftJoin('mp.CommandeTrimestrielle', 'c');
        $queryBuilder->select('g.Nom as nomGroupe, c.Nom as nomCommande, mp.MoisAdmissionCRENASAnneePrecedent, mp.MoisAdmissionProjeteAnneePrecedent, mp.MoisProjectionAnneePrevisionnelle, mp.id');
                    

        if (!empty($search)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('mp.MoisAdmissionCRENASAnneePrecedent', ':search'))
                        ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->groupBy('mp.id')
                    ->orderBy('mp.MoisAdmissionCRENASAnneePrecedent', 'DESC');

        if (is_int($limit) && $limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        if (is_int($start) && $start > 0) {
            $queryBuilder->setFirstResult($start);
        }

      //  $statement = $queryBuilder->execute();

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
//     * @return MoisProjectionsAdmissions[] Returns an array of MoisProjectionsAdmissions objects
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

//    public function findOneBySomeField($value): ?MoisProjectionsAdmissions
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
