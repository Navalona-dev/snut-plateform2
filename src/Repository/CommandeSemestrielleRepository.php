<?php

namespace App\Repository;

use App\Entity\CommandeSemestrielle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeSemestrielle>
 *
 * @method CommandeSemestrielle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeSemestrielle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeSemestrielle[]    findAll()
 * @method CommandeSemestrielle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeSemestrielleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeSemestrielle::class);
    }

    public function getAllCommandeSemestrielle($start = 0, $limit = 0, $search = "")
    {
        $queryBuilder = $this->createQueryBuilder('cs');
        $queryBuilder = $queryBuilder->leftJoin('cs.AnneePrevisionnelle', 'a');
        $queryBuilder->select('cs.Nom, a.Annee, cs.DateDebut, cs.DateFin, cs.IsActive, cs.Slug, cs.id');
                    

        if (!empty($search)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('cs.nom', ':search'))
                        ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->groupBy('cs.id')
                    ->orderBy('cs.Nom', 'DESC');

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
        return $this->createQueryBuilder('cs')
            ->select('COUNT(cs.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return CommandeSemestrielle[] Returns an array of CommandeSemestrielle objects
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

//    public function findOneBySomeField($value): ?CommandeSemestrielle
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
