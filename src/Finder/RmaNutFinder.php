<?php

namespace App\Finder;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\OrderBy;

class RmaNutFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findAllRmaNut()
    {
        $query = $this->em->createQuery("SELECT 
                        r.id AS regionId,
                        r.Nom AS regionNom,
                        d.id AS districtId,
                        d.Nom AS districtNom,
                        rn.id AS rmaNutId,
                        rn.newFileName AS newFileName, 
                        rn.originalFileName AS originalFileName,
                        rn.uploadedDate AS uploadedDate,
                        u.id As idUser,
                        u.Nom AS nomUser,
                        u.Prenoms AS prenomUser,
                        u.Telephone AS telephoneUser,
                        u.email AS email
                    FROM App:District d  
                    LEFT JOIN d.region r
                    LEFT JOIN App:RmaNut rn WITH rn.District = d.id
                    LEFT JOIN App:User u WITH rn.uploadedBy = u.id
                    WHERE rn.newFileName IS NOT NULL
                    ORDER BY r.Nom ASC, d.Nom ASC, rn.uploadedDate DESC");
        $resultLstRMANut = $query->getArrayResult();
        return $resultLstRMANut;
    }

    public function findAllRegionRmaNut($prmRegionId = "", $currentCommande = null)
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select(
            'r.id AS regionId',
            'r.Nom AS regionNom',
            'r.ChefLieu AS regionChefLieu',
            'COUNT(d.id) AS nombreDeDistricts', 
            'SUM(CASE WHEN d.isEligibleForCreni = 1 THEN 1 ELSE 0 END) as nombreDistrictEligibleCreni',
            'SUM(CASE WHEN d.isEligibleForCrenas = 1 THEN 1 ELSE 0 END) as nombreDistrictEligibleCrenas'
        )
        ->from('App:Region', 'r')
        ->leftJoin('App:District', 'd', 'WITH', 'd.region = r.id');

        if (!empty($prmRegionId)) {
            $queryBuilder->andWhere('r.id = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }

        $queryBuilder->groupBy('r.id', 'r.Nom', 'r.ChefLieu')
            ->orderBy('r.Nom', 'ASC');

        $query = $queryBuilder->getQuery();
        $resultLstRegionRMANut = $query->getArrayResult();
        $isNotNullCommande = false;
        if (null != $currentCommande) {
            $isNotNullCommande = true;
        }
        if (isset($resultLstRegionRMANut) && is_array($resultLstRegionRMANut) && count($resultLstRegionRMANut) > 0) {
            for ($i = 0; $i < count($resultLstRegionRMANut); $i++) {
                $regionId = $resultLstRegionRMANut[$i]["regionId"];
                if ($isNotNullCommande) {
                    $nombreRMANuts = $this->getNombreRmaNutFromRegion($regionId, $currentCommande);
                    $resultLstRegionRMANut[$i]["nombreRMANuts"] = $nombreRMANuts;
                }
                
            }
        }
        return $resultLstRegionRMANut;
    }

    public function getNombreRmaNutFromRegion($prmRegionId = null, $currentCommande = null)
    {
        $query = $this->em->createQuery("
            SELECT 
                COUNT(rn.id) AS nombreRMANuts
            FROM App:RmaNut rn  WHERE rn.Region = :prmRegionId and rn.CommandeTrimestrielle =:prmCurrentCommande
        ")
            ->setParameter('prmRegionId', $prmRegionId)
            ->setParameter('prmCurrentCommande', $currentCommande);
        $resultLstRegionCreni = $query->getArrayResult()[0]["nombreRMANuts"];
        return $resultLstRegionCreni;
    }

    public function findDataRmaNutByUserCommandeTrimestrielle($prmUserId, $prmCommandeTrimestrielle)
    {
        $query = $this->em->createQuery(
            'SELECT rn.id AS idCommandeTrimestrielle, 
             rn.uploadedDate AS uploadedDate,
             rn.originalFileName AS originalFileName,
             rn.newFileName AS newFileName
             FROM App:RmaNut rn
             INNER JOIN rn.uploadedBy u
             INNER JOIN rn.CommandeTrimestrielle ct
             WHERE rn.uploadedBy = :prmUserId
             AND rn.CommandeTrimestrielle = :prmCommandeTrimestrielle'
        )
            ->setParameter('prmUserId', $prmUserId)
            ->setParameter('prmCommandeTrimestrielle', $prmCommandeTrimestrielle)
            ->setMaxResults(1);

        $resultDataCommandeTrimestrielle = $query->getOneOrNullResult();
        return $resultDataCommandeTrimestrielle;
    }

    public function findListRMANutByRegion($prmRegionId)
    {
        $query = $this->em->createQuery("SELECT 
                        r.id AS regionId,
                        r.Nom AS regionNom,
                        d.id AS districtId,
                        d.Nom AS districtNom,
                        rn.id AS rmaNutId,
                        rn.newFileName AS newFileName, 
                        rn.originalFileName AS originalFileName,
                        rn.uploadedDate AS uploadedDate,
                        u.id As idUser,
                        u.Nom AS nomUser,
                        u.Prenoms AS prenomUser,
                        u.Telephone AS telephoneUser,
                        u.email AS email
                    FROM App:District d  
                    LEFT JOIN d.region r
                    LEFT JOIN App:RmaNut rn WITH rn.District = d.id
                    LEFT JOIN App:User u WITH rn.uploadedBy = u.id
                    WHERE d.region = :prmRegionId")
            ->setParameter('prmRegionId', $prmRegionId);
        $resultLstRMANut = $query->getArrayResult();
        return $resultLstRMANut;
    }

    public function findListRMANutByDistrict($prmDistrictId)
    {
        $query = $this->em->createQuery("SELECT 
                        r.id AS regionId,
                        r.Nom AS regionNom,
                        d.id AS districtId,
                        d.Nom AS districtNom,
                        rn.id AS rmaNutId,
                        rn.newFileName AS newFileName, 
                        rn.originalFileName AS originalFileName,
                        rn.uploadedDate AS uploadedDate,
                        u.id As idUser,
                        u.Nom AS nomUser,
                        u.Prenoms AS prenomUser,
                        u.Telephone AS telephoneUser,
                        u.email AS email
                    FROM App:District d  
                    LEFT JOIN d.region r
                    LEFT JOIN App:RmaNut rn WITH rn.District = d.id
                    LEFT JOIN App:User u WITH rn.uploadedBy = u.id
                    WHERE d.id = :prmDistrictId")
            ->setParameter('prmDistrictId', $prmDistrictId);
        $resultLstRMANut = $query->getArrayResult();
        return $resultLstRMANut;
    }

    public function findRmanutById($prmRmnautId)
    {
        $query = $this->em->createQuery("SELECT 
                        r.id AS regionId,
                        r.Nom AS regionNom,
                        d.id AS districtId,
                        d.Nom AS districtNom,
                        rn.id AS rmaNutId,
                        rn.newFileName AS newFileName, 
                        rn.originalFileName AS originalFileName,
                        rn.uploadedDate AS uploadedDate,
                        u.id As idUser,
                        u.Nom AS nomUser,
                        u.Prenoms AS prenomUser,
                        u.Telephone AS telephoneUser,
                        u.email AS email
                    FROM App:District d  
                    LEFT JOIN d.region r
                    LEFT JOIN App:RmaNut rn WITH rn.District = d.id
                    LEFT JOIN App:User u WITH rn.uploadedBy = u.id
                    WHERE rn.id = :prmRegionId")
            ->setParameter('prmRegionId', $prmRmnautId);
        $resultLstRMANut = $query->getArrayResult();
        return $resultLstRMANut;
    }

    public function getDistrictsDataRmaNutByRegion($prmRegionId)
    {
        $query = $this->em->createQuery("
                    SELECT 
                        d.id as districtId, 
                        d.Nom as districtName,
                        d.isEligibleForCreni as isEligibleForCreni,
                        d.isEligibleForCrenas as isEligibleForCrenas,
                        COUNT(rn.id) as countRMANut
                    FROM App:District d
                    LEFT JOIN App:RmaNut rn WITH rn.District = d.id
                    WHERE d.region = :prmRegionId
                    GROUP BY d.id, d.Nom
                ")
            ->setParameter('prmRegionId', $prmRegionId);
        $resultLstRMANut = $query->getArrayResult();
        return $resultLstRMANut;
    }

    public function getInfoDistrictWithRmaNutByUserId($prmUserId)
    {
        $query = $this->em->createQuery("SELECT 
                        r.id AS regionId,
                        r.Nom AS regionNom,
                        d.id AS districtId,
                        d.Nom AS districtNom, 
                        u.Nom AS nomUser,
                        u.Prenoms AS prenomUser,
                        u.Telephone AS telephoneUser,
                        u.email AS email,
                        p.id AS provinceId,
                        p.NomFR AS provinceNom,
                        g.id AS groupeId,
                        g.Nom AS groupeNom,
                        rn.id AS rmaNutId,
                        rn.newFileName AS newFileName, 
                        rn.originalFileName AS originalFileName,
                        rn.uploadedDate AS uploadedDate
                    FROM App:District d  
                    INNER JOIN App:User u WITH u.District = d.id
                    INNER JOIN App:Region r WITH d.region = r.id  
                    INNER JOIN App:Province p WITH r MEMBER OF p.regions
                    INNER JOIN App:Groupe g WITH p MEMBER OF g.Provinces
                    INNER JOIN App:RmaNut rn WITH rn.District = d.id
                    WHERE u.id = :prmUserId")
            ->setParameter('prmUserId', $prmUserId);
        $resultDistrict = $query->getArrayResult()[0];
        return $resultDistrict;
    }

    /**
     * Liste des districts avec ses détails.
     * 
     * @return mixed
     */
    public function FindAllDistrictWithDetails($prmRegionId = "")
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select(
            'province.id AS provinceId',
            'province.NomFR AS provinceNom',
            'region.id AS regionId',
            'region.Nom AS regionNom',
            'district.id AS districtId',
            'district.Nom AS districtNom',
            'district.isEligibleForCreni AS isEligibleForCreni',
            'district.isEligibleForCrenas AS isEligibleForCrenas',
            'CASE WHEN COUNT(rma_nut.id) > 0 THEN 1 ELSE 0 END AS promptitude',
            'CASE WHEN COUNT(rma_nut.id) > 0 THEN 1 ELSE 0 END AS a_fichier_rma_nut',
            'CASE WHEN COUNT(data_crenas.id) > 0 THEN 1 ELSE 0 END AS a_data_crenas',
            'CASE WHEN COUNT(data_creni.id) > 0 THEN 1 ELSE 0 END AS a_data_creni',
            '(((( CASE WHEN COUNT(rma_nut.id) > 0 THEN 1 ELSE 0 END
                + CASE WHEN COUNT(data_crenas.id) > 0 THEN 1 ELSE 0 END
                + CASE WHEN COUNT(data_creni.id) > 0 THEN 1 ELSE 0 END)) * 100) / 3) AS pourcentage_completude'
        )
        ->from('App:District', 'district')
        ->join('district.region', 'region')
        ->join('region.province', 'province')
        ->leftJoin('App:RmaNut', 'rma_nut', 'WITH', 'rma_nut.District = district.id');

        if (!empty($prmRegionId)) {
            $queryBuilder->andWhere('rma_nut.Region = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }

        $queryBuilder->leftJoin('App:User', 'user', 'WITH', 'user.District = district.id')
            ->leftJoin('App:DataCrenas', 'data_crenas', 'WITH', 'user.id = data_crenas.User')
            ->leftJoin('App:DataCreni', 'data_creni', 'WITH', 'user.id = data_creni.User')
            ->groupBy('district.id', 'district.Nom', 'region.id', 'region.Nom', 'province.id', 'province.NomFR')
            ->orderBy('province.NomFR, region.Nom, district.Nom');

        $query = $queryBuilder->getQuery();
        $resultDistrict = $query->getArrayResult();

        return $resultDistrict;
    }


    /**
     * Liste des districts avec Rma-Nut.
     * 
     * @return mixed
     */
    public function FindAllDistrictWithRmaNut($prmRegionId = "")
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select(
            'province.id AS provinceId',
            'province.NomFR AS provinceNom',
            'region.id AS regionId',
            'region.Nom AS regionNom',
            'district.id AS districtId',
            'district.Nom AS districtNom',
            'CASE WHEN COUNT(rma_nut.id) > 0 THEN 1 ELSE 0 END AS a_fichier_rma_nut',
            'CASE WHEN COUNT(data_crenas.id) > 0 THEN 1 ELSE 0 END AS a_data_crenas',
            'CASE WHEN COUNT(data_creni.id) > 0 THEN 1 ELSE 0 END AS a_data_creni',
            '(((( CASE WHEN COUNT(rma_nut.id) > 0 THEN 1 ELSE 0 END
                + CASE WHEN COUNT(data_crenas.id) > 0 THEN 1 ELSE 0 END
                + CASE WHEN COUNT(data_creni.id) > 0 THEN 1 ELSE 0 END)) * 100) / 3) AS pourcentage_completude'
        )
        ->from('App:District', 'district')
        ->join('district.region', 'region')
        ->join('region.province', 'province')
        ->leftJoin('App:RmaNut', 'rma_nut', 'WITH', 'rma_nut.District = district.id');

        if (!empty($prmRegionId)) {
            $queryBuilder->andWhere('rma_nut.Region = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }

        $queryBuilder->leftJoin('App:User', 'user', 'WITH', 'user.District = district.id')
            ->leftJoin('App:DataCrenas', 'data_crenas', 'WITH', 'user.id = data_crenas.User')
            ->leftJoin('App:DataCreni', 'data_creni', 'WITH', 'user.id = data_creni.User')
            ->groupBy('rma_nut.id')
            ->orderBy('province.NomFR, region.Nom, district.Nom');

        $query = $queryBuilder->getQuery();
        $resultDistrict = $query->getArrayResult();

        return $resultDistrict;
    }


    /**
     * Liste des districts;
     */
    public function FindAllDistrict()
    {
        $query = $this->em->createQueryBuilder()
            ->select('d.id, d.Nom')
            ->from('App:District', 'd');
        $resultDistrict = $query->getQuery()->getResult();
        return $resultDistrict;
    }

    /**
     * Liste des districts par région
     */
    public function FindAllDistrictByRegionId($prmRegionId)
    {
        $query = $this->em->createQueryBuilder()
            ->select('d.id, d.Nom')
            ->from('App:District', 'd')
            ->where('d.region = :prmRegionId')
            ->setParameter('prmRegionId', $prmRegionId);
        $resultDistrict = $query->getQuery()->getResult();
        return $resultDistrict;
    }

    /**
    * Obtenir un district par son id;
    */
    public function FindDistrictById($id)
    {
        $query = $this->em->createQueryBuilder()
            ->select('d.id, d.Nom')
            ->from('App:District', 'd')
            ->where('d.id = :id')
            ->setParameter('id', $id);
        $resultDistrict = $query->getQuery()->getResult();
        return $resultDistrict;
    }

    /**
     * Liste des RMA-NUT entre deux dates
     * 
     * @return mixed
     */
    public function findRmaNutsBetweenDates(\DateTimeInterface $dateDebut, \DateTimeInterface $dateFin)
    {
        $query = $this->em->createQuery('
            SELECT rn
            FROM App:RmaNut rn
            WHERE rn.uploadedDate BETWEEN :dateDebut AND :dateFin
        ')
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin);

        return $query->getResult();
    }
}
