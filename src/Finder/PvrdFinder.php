<?php

namespace App\Finder;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;

Class PvrdFinder
{
    private $em; 

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em;
    }

    public function findDataPvrdByUserCommandeTrimestrielle($prmUserId, $prmCommandeTrimestrielle, $idPvrd = null)
    {
        if (null != $idPvrd) {
            $query = $this->em->createQuery(
                'SELECT 
                    pv.id AS IdPvrd, 
                    pv.Site AS Site,
                    pv.DateReception AS DateReception,
                    pv.DatePvrd AS DatePvrd,
                    pv.DateTeleversement AS DateTeleversement,
                    pv.NumeroBonLivraison AS NumeroBonLivraison,
                    pv.Fournisseur AS Fournisseur,
                    pv.UploadedDateTime AS UploadedDateTime,
                    pv.NewFileName AS NewFileName,
                    d.id AS IdDistrict,
                    d.Nom As NomDistrict,
                    r.id AS IdRegion,
                    r.Nom AS NomRegion
                 FROM App:Pvrd pv
                 INNER JOIN pv.ResponsableDistrict u 
                 INNER JOIN pv.District d
                 INNER JOIN pv.Region r
                 WHERE pv.ResponsableDistrict = :prmUserId and pv.id =:idPvrd'
            )

            ->setParameter('idPvrd', $idPvrd);
        } else {
            $query = $this->em->createQuery(
                'SELECT 
                    pv.id AS IdPvrd, 
                    pv.Site AS Site,
                    pv.DateReception AS DateReception,
                    pv.DatePvrd AS DatePvrd,
                    pv.DateTeleversement AS DateTeleversement,
                    pv.NumeroBonLivraison AS NumeroBonLivraison,
                    pv.Fournisseur AS Fournisseur,
                    pv.UploadedDateTime AS UploadedDateTime,
                    pv.NewFileName AS NewFileName,
                    d.id AS IdDistrict,
                    d.Nom As NomDistrict,
                    r.id AS IdRegion,
                    r.Nom AS NomRegion
                 FROM App:Pvrd pv
                 INNER JOIN pv.ResponsableDistrict u 
                 INNER JOIN pv.District d
                 INNER JOIN pv.Region r
                 WHERE pv.ResponsableDistrict = :prmUserId'
            );
        }
        
        $query ->setParameter('prmUserId', $prmUserId)
        //->setParameter('prmCommandeTrimestrielle', $prmCommandeTrimestrielle)
        ->setMaxResults(1);

        $resultDataCommandeTrimestrielle = $query->getOneOrNullResult();
        return $resultDataCommandeTrimestrielle;
    }

    public function findAllTauxPvrd($prmRegionId = "")
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select(
                'ct.id AS idTrimestrielle',
                'ct.Slug AS slugTrimestrielle',
                'COUNT(DISTINCT pv.id) AS NbrePvrdRecuTrimestrielle'
            )
            ->from('App:CommandeTrimestrielle', 'ct')
            ->leftJoin('App:PvrdProduit', 'pvp', 'WITH', 'pvp.Periode = ct.Slug')
            ->leftJoin('App:Pvrd', 'pv', 'WITH', 'pvp.Pvrd = pv.id');
        if (!empty($prmRegionId)) {
            $queryBuilder->andWhere('pv.Region = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }
        $queryBuilder->groupBy('ct.id', 'ct.Slug');  
        $query = $queryBuilder->getQuery();
        $resultLstTrimestrielle = $query->getArrayResult();

        if (isset($resultLstTrimestrielle) && is_array($resultLstTrimestrielle) && count($resultLstTrimestrielle) > 0) {
            for ($i = 0; $i < count($resultLstTrimestrielle); $i++) { 
                $calculTauxDEnvoi = ($resultLstTrimestrielle[$i]["NbrePvrdRecuTrimestrielle"] * 114) / 100;
                $resultLstTrimestrielle[$i]["TauxEnvoiPvrd"] = $calculTauxDEnvoi;
            }
        } 

        return $resultLstTrimestrielle;

    }

    public function findAllRegionPvrd($prmRegionId = null, $currentCommande = null)
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select(
            'r.id AS regionId',
            'r.Nom AS regionNom',
            'r.ChefLieu AS regionChefLieu',
            'COUNT(d.id) AS nombreDeDistricts'
        )
        ->from('App:Region', 'r')
        ->leftJoin('App:District', 'd', 'WITH', 'd.region = r.id');
    
        if (null != $prmRegionId) {
            $queryBuilder->andWhere('r.id = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }
    
        $queryBuilder->groupBy('r.id', 'r.Nom', 'r.ChefLieu')
            ->orderBy('r.Nom', 'ASC');
    
        $query = $queryBuilder->getQuery();
        $resultLstRegionPvrd = $query->getArrayResult();

        $isNotNullCommande = false;
        if (null != $currentCommande) {
            $isNotNullCommande = true;
        }

        if (isset($resultLstRegionPvrd) && is_array($resultLstRegionPvrd) && count($resultLstRegionPvrd) > 0) {
            for ($i = 0; $i < count($resultLstRegionPvrd); $i++) {
                $regionId = $resultLstRegionPvrd[$i]["regionId"];
                if ($isNotNullCommande) {
                    $nombrePvrds = $this->getNombrePvrdFromRegion($regionId, $currentCommande);
                    $resultLstRegionPvrd[$i]["nombrePvrds"] = $nombrePvrds;
                }
            }
        }
        return $resultLstRegionPvrd;
    }

    public function getNombrePvrdFromRegion($prmRegionId = null, $currentCommande = null)
    {
        if (null != $currentCommande) {
            $query = $this->em->createQuery("
            SELECT 
                COUNT(pv.id) AS nombrePvrds
            FROM App:Pvrd pv  WHERE pv.Region = :prmRegionId and rn.CommandeTrimestrielle =:prmCurrentCommande
        ")
            ->setParameter('prmRegionId', $prmRegionId)
            ->setParameter('prmCurrentCommande', $currentCommande);
        $resultLstRegionCreni = $query->getArrayResult()[0]["nombrePvrds"];
        } else {
            $query = $this->em->createQuery("
            SELECT 
                COUNT(pv.id) AS nombrePvrds
            FROM App:Pvrd pv  WHERE pv.Region = :prmRegionId
        ")
            ->setParameter('prmRegionId', $prmRegionId);
        $resultLstRegionCreni = $query->getArrayResult()[0]["nombrePvrds"];
        }
        
        return $resultLstRegionCreni;
    }

    public function findListPvrdByDistrict($prmDistrictId)
    {
        $query = $this->em->createQuery("SELECT 
                                            d.id AS districtId,
                                            d.Nom AS districtNom,
                                            pv.id AS IdPvrd, 
                                            pv.Site AS Site,
                                            pv.DateReception AS DateReception, 
                                            pv.DatePvrd AS DatePvrd,
                                            pv.DateTeleversement AS DateTeleversement,
                                            pv.NumeroBonLivraison AS NumeroBonLivraison,
                                            pv.Fournisseur AS Fournisseur,
                                            pv.UploadedDateTime AS UploadedDateTime,
                                            pv.NewFileName AS NewFileName,
                                            u.id As idUser,
                                            u.Nom AS nomUser,
                                            u.Prenoms AS prenomUser,
                                            u.Telephone AS telephoneUser,
                                            u.email AS email 
                                        FROM App:District d  
                                        LEFT JOIN App:Pvrd pv WITH pv.District = d.id
                                        LEFT JOIN App:User u WITH pv.ResponsableDistrict = u.id
                                        WHERE d.id = :prmDistrictId")
                    ->setParameter('prmDistrictId', $prmDistrictId);
     
        try {
            $resultLstPvrd = $query->getArrayResult(); 
            if (is_array($resultLstPvrd) && count($resultLstPvrd) > 0) {

                for ($i = 0; $i < count($resultLstPvrd); $i++) {
                    $dateReception = $resultLstPvrd[$i]["DateReception"];
                    $dateTeleversement = $resultLstPvrd[$i]["DateTeleversement"];
                
                    // Vérifier si les dates sont bien des objets DateTime
                    if ($dateReception instanceof DateTime && $dateTeleversement instanceof DateTime) {
                        $days = 0;
                        $modifiedDateReception = clone $dateReception;
                
                        while ($modifiedDateReception < $dateTeleversement) {
                            if ($modifiedDateReception->format('N') != 6 && $modifiedDateReception->format('N') != 7) {
                                $days++;
                            }
                            $modifiedDateReception->modify('+1 day');
                        }
                
                        $resultLstPvrd[$i]["JoursOuvrablesDifference"] = $days;
                    } else {
                        // Gérer le cas où l'une des valeurs n'est pas un objet DateTime
                        $resultLstPvrd[$i]["JoursOuvrablesDifference"] = 0;
                    }
                }
                
            }
        } catch (\Exception $e) {
            // Gérer l'erreur
            // Par exemple, afficher le message d'erreur pour le débogage
            echo $e->getMessage();
            // Peut-être retourner une valeur par défaut ou autre chose en cas d'erreur
            return [];
        }
        
        return $resultLstPvrd;
    }

    public function findListPvrdByRegion($prmRegionId)
    {
        $query = $this->em->createQuery("SELECT 
                                            r.id AS regionId,
                                            r.Nom AS regionNom,
                                            d.id AS districtId,
                                            d.Nom AS districtNom,
                                            pv.id AS IdPvrd, 
                                            pv.Site AS Site,
                                            pv.DateReception AS DateReception, 
                                            pv.DatePvrd AS DatePvrd,
                                            pv.DateTeleversement AS DateTeleversement,
                                            pv.NumeroBonLivraison AS NumeroBonLivraison,
                                            pv.Fournisseur AS Fournisseur,
                                            pv.UploadedDateTime AS UploadedDateTime,
                                            pv.NewFileName AS NewFileName,
                                            u.id As idUser,
                                            u.Nom AS nomUser,
                                            u.Prenoms AS prenomUser,
                                            u.Telephone AS telephoneUser,
                                            u.email AS email 
                                        FROM App:District d  
                                        LEFT JOIN d.region r 
                                        LEFT JOIN App:Pvrd pv WITH pv.District = d.id
                                        LEFT JOIN App:User u WITH pv.ResponsableDistrict = u.id
                                        WHERE d.region = :prmRegionId")
                    ->setParameter('prmRegionId', $prmRegionId);

        try {
            $resultLstPvrd = $query->getArrayResult(); 
            if (is_array($resultLstPvrd) && count($resultLstPvrd) > 0) {

                for ($i = 0; $i < count($resultLstPvrd); $i++) {
                    $dateReception = $resultLstPvrd[$i]["DateReception"];
                    $dateTeleversement = $resultLstPvrd[$i]["DateTeleversement"];
                
                    // Vérifier si les dates sont bien des objets DateTime
                    if ($dateReception instanceof DateTime && $dateTeleversement instanceof DateTime) {
                        $days = 0;
                        $modifiedDateReception = clone $dateReception;
                
                        while ($modifiedDateReception < $dateTeleversement) {
                            if ($modifiedDateReception->format('N') != 6 && $modifiedDateReception->format('N') != 7) {
                                $days++;
                            }
                            $modifiedDateReception->modify('+1 day');
                        }
                
                        $resultLstPvrd[$i]["JoursOuvrablesDifference"] = $days;
                    } else {
                        // Gérer le cas où l'une des valeurs n'est pas un objet DateTime
                        $resultLstPvrd[$i]["JoursOuvrablesDifference"] = 0;
                    }
                }
                
            }
        } catch (\Exception $e) {
            // Gérer l'erreur
            // Par exemple, afficher le message d'erreur pour le débogage
            echo $e->getMessage();
            // Peut-être retourner une valeur par défaut ou autre chose en cas d'erreur
            return [];
        }
        
        return $resultLstPvrd;
    } 
 
    public function getDistrictsDataPvrdByRegion($prmRegionId)
    {
        $query = $this->em->createQuery("
                    SELECT 
                        d.id as districtId, 
                        d.Nom as districtName,
                        d.isEligibleForCreni as isEligibleForCreni,
                        d.isEligibleForCrenas as isEligibleForCrenas,
                        COUNT(pv.id) as countPvrd
                    FROM App:District d
                    LEFT JOIN App:Pvrd pv WITH pv.District = d.id
                    WHERE d.region = :prmRegionId
                    GROUP BY d.id, d.Nom
                ")
            ->setParameter('prmRegionId', $prmRegionId);
        $resultLstPvrd = $query->getArrayResult();
        return $resultLstPvrd;
    }

}

?>