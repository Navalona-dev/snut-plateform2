<?php

namespace App\Repository;

use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Groupe>
 *
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }

    public function getAllGroupe($start = 0, $limit = 0, $search = "")
    {

        $sql = "SELECT g.id, a.annee, g.nom, GROUP_CONCAT(DISTINCT p.nom_fr) as provinces, GROUP_CONCAT(DISTINCT d.nom) as districts FROM groupe g LEFT JOIN groupe_province gp on gp.groupe_id = g.id  LEFT JOIN province p ON p.id = gp.province_id LEFT JOIN groupe_district gd on gd.groupe_id = g.id LEFT JOIN district d ON d.id = gd.district_id LEFT JOIN annee_previsionnelle a on a.id = g.annee_id group by g.id order by g.nom ASC";

        $conn = $this->_em->getConnection();
        $query = $conn->prepare($sql);

        $statement = $query->execute();

        return $statement->fetchAllAssociative(); // 
       
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Groupe[] Returns an array of Groupe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Groupe
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
