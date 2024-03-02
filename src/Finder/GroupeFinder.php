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
                p.NomFR AS provinceNomFR,
                g.zone As type'
                )
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

    public function findDataGroupe($prmAnneeId, $prmProvinceId, $prmDistrictId = null)
    {
        $queryBuilder = $this->em->createQueryBuilder();
       
        if (null != $prmDistrictId) {
            $queryBuilder 
            ->select('g.id AS idGroupe, 
                g.Nom AS nomGroupe, 
                a.id AS anneeId,
                a.Annee AS annee,
                p.id AS provinceId,
                p.NomFR AS provinceNomFR,
                d.Nom As nomDistrict,
                d.type As type'
                )
            ->from('App:Groupe', 'g')
            ->join('g.Annee', 'a') 
            ->join('g.Provinces', 'p')
            ->join('g.districts', 'd')  
            ->where('a.id = :anneeId') 
            ->andWhere('p.id = :provinceId')
            ->andWhere('d.id = :districtId')  
            ->setParameter('anneeId', $prmAnneeId)
            ->setParameter('provinceId', $prmProvinceId)
            ->setParameter('districtId', $prmDistrictId);             
        } else {
            $queryBuilder 
            ->select('g.id AS idGroupe, 
                g.Nom AS nomGroupe, 
                a.id AS anneeId,
                a.Annee AS annee,
                p.id AS provinceId,
                p.NomFR AS provinceNomFR'
                )
            ->from('App:Groupe', 'g')
            ->join('g.Annee', 'a') 
            ->join('g.Provinces', 'p')
            ->join('g.districts', 'd')  
            ->where('a.id = :anneeId') 
            ->andWhere('p.id = :provinceId')
            ->setParameter('anneeId', $prmAnneeId)
            ->setParameter('provinceId', $prmProvinceId);
        }
        

            
            
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

    public function findAllDataGroupeRegionale($prmAnneeId, $regionId = null)
    {
        /*$queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('g.id AS idGroupe, 
                g.Nom AS nomGroupe, 
                a.id AS anneeId,
                a.Annee AS annee,
                p.id AS provinceId,
                p.NomFR AS provinceNomFR,
                d.Nom As nomDistrict
                ')
            ->from('App:Groupe', 'g')
            ->join('g.Annee', 'a') 
            ->join('g.Provinces', 'p')
            ->join('g.districts', 'd') 
            ->where('a.id = :anneeId')  
            ->setParameter('anneeId', $prmAnneeId)
            ->groupBy('g.id');
        $resultDataGroup = $queryBuilder->getQuery()->getArrayResult(); */
        if (null != $regionId) {
            $sql = "SELECT g.id as idGroupe, a.annee, g.nom as nomGroupe, GROUP_CONCAT(DISTINCT p.nom_fr) as provinces, GROUP_CONCAT(DISTINCT d.nom) as districts FROM groupe g LEFT JOIN groupe_province gp on gp.groupe_id = g.id  LEFT JOIN province p ON p.id = gp.province_id LEFT JOIN groupe_district gd on gd.groupe_id = g.id LEFT JOIN district d ON d.id = gd.district_id LEFT JOIN annee_previsionnelle a on a.id = g.annee_id where a.id = ".$prmAnneeId." and d.region_id = ".$regionId." group by g.id order by g.nom ASC";
        } else {
            $sql = "SELECT g.id as idGroupe, a.annee, g.nom as nomGroupe, GROUP_CONCAT(DISTINCT p.nom_fr) as provinces, GROUP_CONCAT(DISTINCT d.nom) as districts FROM groupe g LEFT JOIN groupe_province gp on gp.groupe_id = g.id  LEFT JOIN province p ON p.id = gp.province_id LEFT JOIN groupe_district gd on gd.groupe_id = g.id LEFT JOIN district d ON d.id = gd.district_id LEFT JOIN annee_previsionnelle a on a.id = g.annee_id where a.id = ".$prmAnneeId." group by g.id order by g.nom ASC";    
        }
         
        $conn = $this->em->getConnection();
        $query = $conn->prepare($sql);

        $statement = $query->execute();

        return $statement->fetchAllAssociative(); // 

        //return $resultDataGroup;
    }
}

?>