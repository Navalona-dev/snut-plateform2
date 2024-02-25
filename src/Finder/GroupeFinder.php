<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class GroupeFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    }
    
    public function findById($prmGroupeId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('g.id AS idGroupe, 
                g.Nom AS nomGroupe, 
                a.id AS anneeId,
                a.Annee AS annee,
                p.id AS provinceId,
                p.NomFR AS provinceNomFR')
            ->from('App:Groupe', 'g')
            ->join('g.Annee', 'a') 
            ->join('g.Provinces', 'p')  
            ->andWhere('g.id = :prmGroupeId') 
            ->setParameter('prmGroupeId', $prmGroupeId); 
        $resultDataGroup = $queryBuilder->getQuery()->getArrayResult(); 
        return $resultDataGroup;
    }

    public function findByRegionId($prmRegionId)
    {
        $query = $this->em->createQuery("
                    SELECT 
                        g.id AS idGroupe,
                        g.Nom AS nomGroupe,
                        r.id AS idRegion,
                        r.Nom AS nomRegion
                    FROM App:Groupe g  
                    INNER JOIN g.Provinces p 
                    INNER JOIN p.regions r 
                    WHERE r.id = :prmRegionId")
        ->setParameter('prmRegionId', $prmRegionId);
        $resultLstRMANut = $query->getArrayResult()[0];
        return $resultLstRMANut; 
    }

    public function findDataGroupe($prmAnneeId, $prmProvinceId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('g.id AS idGroupe, 
                g.Nom AS nomGroupe, 
                a.id AS anneeId,
                a.Annee AS annee,
                p.id AS provinceId,
                p.NomFR AS provinceNomFR')
            ->from('App:Groupe', 'g')
            ->join('g.Annee', 'a') 
            ->join('g.Provinces', 'p') 
            ->where('a.id = :anneeId') 
            ->andWhere('p.id = :provinceId') 
            ->setParameter('anneeId', $prmAnneeId)
            ->setParameter('provinceId', $prmProvinceId); 
        $resultDataGroup = $queryBuilder->getQuery()->getArrayResult(); 
        if (!empty($resultDataGroup)) {
            $resultDataGroup = $resultDataGroup[0];
        } else {
            $resultDataGroup = null; // ou une autre valeur par défaut
        }
        return $resultDataGroup;
    }

    public function findDataGroupeByAnneeId($prmAnneeId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('g.id AS idGroupe, 
                g.Nom AS nomGroupe, 
                a.id AS anneeId,
                a.Annee AS annee,
                p.id AS provinceId,
                p.NomFR AS provinceNomFR')
            ->from('App:Groupe', 'g')
            ->join('g.Annee', 'a') 
            ->join('g.Provinces', 'p') 
            ->where('a.id = :anneeId') 
            ->andWhere('p.id = :provinceId') 
            ->setParameter('anneeId', $prmAnneeId); 
        $resultDataGroup = $queryBuilder->getQuery()->getArrayResult()[0]; 
        return $resultDataGroup;
    }

    public function findAllDataGroupe($prmAnneeId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('g.id AS idGroupe, 
                g.Nom AS nomGroupe, 
                a.id AS anneeId,
                a.Annee AS annee,
                p.id AS provinceId,
                p.NomFR AS provinceNomFR')
            ->from('App:Groupe', 'g')
            ->join('g.Annee', 'a') 
            ->join('g.Provinces', 'p') 
            ->where('a.id = :anneeId')  
            ->setParameter('anneeId', $prmAnneeId);
        $resultDataGroup = $queryBuilder->getQuery()->getArrayResult(); 
        return $resultDataGroup;
    }
}

?>