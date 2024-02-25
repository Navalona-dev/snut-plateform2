<?php

namespace App\Repository;

use App\Entity\CommandeTrimestrielle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeTrimestrielle>
 *
 * @method CommandeTrimestrielle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeTrimestrielle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeTrimestrielle[]    findAll()
 * @method CommandeTrimestrielle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeTrimestrielleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeTrimestrielle::class);
    }

    public function getAllCommandeTrimestrielle($start = 0, $limit = 0, $search = "")
    {
        //$conn = $this->_em->getConnection();

        $queryBuilder = $this->createQueryBuilder('ct');
        $queryBuilder = $queryBuilder->leftJoin('ct.AnneePrevisionnelle', 'a');
        $queryBuilder->select('ct.Nom, a.Annee, ct.DateDebut, ct.DateFin, ct.isActive, ct.Slug, ct.id');
                    

        if (!empty($search)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('ct.nom', ':search'))
                        ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->groupBy('ct.id')
                    ->orderBy('ct.Nom', 'DESC');

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
        return $this->createQueryBuilder('ct')
            ->select('COUNT(ct.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return CommandeTrimestrielle[] Returns an array of CommandeTrimestrielle objects
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

//    public function findOneBySomeField($value): ?CommandeTrimestrielle
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
