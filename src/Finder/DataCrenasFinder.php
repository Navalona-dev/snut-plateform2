<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class DataCrenasFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    }

    public function findDataCrenasById($prmId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dc.id, dc.totalCRENASAnneePrecedent, dc.totalProjeteAnneePrecedent, dc.totalAnneeProjection, dc.resultatAnneePrecedent, dc.resultatAnneeProjection,
                    dc.besoinAPTE, dc.besoinAMOX, dc.besoinFichePatient, dc.besoinRegistre, dc.besoinCarnetRapportCRENAS, 
                    dc.quantite01PnSDUCartonBSD, dc.dateExpiration01PnSDUCartonBSD, dc.quantite02PnSDUCartonBSD, dc.dateExpiration02PnSDUCartonBSD, dc.quantite03PnSDUCartonBSD, dc.dateExpiration03PnSDUCartonBSD, dc.quantite04PnSDUCartonBSD, dc.dateExpiration04PnSDUCartonBSD, dc.totalPnSDUCartonBSD, 
                    dc.quantite01PnSDUCartonCSB, dc.dateExpiration01PnSDUCartonCSB, dc.quantite02PnSDUCartonCSB, dc.dateExpiration02PnSDUCartonCSB, dc.quantite03PnSDUCartonCSB, dc.dateExpiration03PnSDUCartonCSB, dc.quantite04PnSDUCartonCSB, dc.dateExpiration04PnSDUCartonCSB, dc.totalPnSDUCartonCSB,
                    dc.totalPnSDUCartonSDSP,
                    dc.quantite01AmoxSDUCartonBSD, dc.dateExpiration01AmoxSDUCartonBSD, dc.quantite02AmoxSDUCartonBSD, dc.dateExpiration02AmoxSDUCartonBSD, dc.quantite03AmoxSDUCartonBSD, dc.dateExpiration03AmoxSDUCartonBSD, dc.quantite04AmoxSDUCartonBSD, dc.dateExpiration04AmoxSDUCartonBSD, dc.totalAmoxSDUCartonBSD,
                    dc.quantite01AmoxSDUCartonCSB, dc.dateExpiration01AmoxSDUCartonCSB, dc.quantite02AmoxSDUCartonCSB, dc.dateExpiration02AmoxSDUCartonCSB, dc.quantite03AmoxSDUCartonCSB, dc.dateExpiration03AmoxSDUCartonCSB, dc.quantite04AmoxSDUCartonCSB, dc.dateExpiration04AmoxSDUCartonCSB, dc.totalAmoxSDUCartonCSB,
                    dc.totalAmoxSDUCartonSDSP,
                    dc.sduFiche, dc.nbrTotalCsb, dc.nbrCSBCRENAS, dc.tauxCouvertureCRENAS, dc.nbrCSBCRENASCommande, dc.tauxEnvoiCommandeCSBCRENAS, 
                    u.id AS userId, u.Nom AS nomUser, u.Prenoms AS prenomUser'])
        ->from('App:DataCrenas', 'dc') 
            ->join('dc.User', 'u') 
            ->andWhere('dc.id= :dcId') 
            ->setParameter('dcId', $prmId); 
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataGroup = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataGroup = $result[0];
        }
        return $resultDataGroup;
    }

    public function findDataCrenasByUserId($prmUserId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dc.id, dc.totalCRENASAnneePrecedent, dc.totalProjeteAnneePrecedent, dc.totalAnneeProjection, dc.resultatAnneePrecedent, dc.resultatAnneeProjection,
                    dc.besoinAPTE, dc.besoinAMOX, dc.besoinFichePatient, dc.besoinRegistre, dc.besoinCarnetRapportCRENAS, 
                    dc.quantite01PnSDUCartonBSD, dc.dateExpiration01PnSDUCartonBSD, dc.quantite02PnSDUCartonBSD, dc.dateExpiration02PnSDUCartonBSD, dc.quantite03PnSDUCartonBSD, dc.dateExpiration03PnSDUCartonBSD, dc.quantite04PnSDUCartonBSD, dc.dateExpiration04PnSDUCartonBSD, dc.totalPnSDUCartonBSD, 
                    dc.quantite01PnSDUCartonCSB, dc.dateExpiration01PnSDUCartonCSB, dc.quantite02PnSDUCartonCSB, dc.dateExpiration02PnSDUCartonCSB, dc.quantite03PnSDUCartonCSB, dc.dateExpiration03PnSDUCartonCSB, dc.quantite04PnSDUCartonCSB, dc.dateExpiration04PnSDUCartonCSB, dc.totalPnSDUCartonCSB,
                    dc.totalPnSDUCartonSDSP,
                    dc.quantite01AmoxSDUCartonBSD, dc.dateExpiration01AmoxSDUCartonBSD, dc.quantite02AmoxSDUCartonBSD, dc.dateExpiration02AmoxSDUCartonBSD, dc.quantite03AmoxSDUCartonBSD, dc.dateExpiration03AmoxSDUCartonBSD, dc.quantite04AmoxSDUCartonBSD, dc.dateExpiration04AmoxSDUCartonBSD, dc.totalAmoxSDUCartonBSD,
                    dc.quantite01AmoxSDUCartonCSB, dc.dateExpiration01AmoxSDUCartonCSB, dc.quantite02AmoxSDUCartonCSB, dc.dateExpiration02AmoxSDUCartonCSB, dc.quantite03AmoxSDUCartonCSB, dc.dateExpiration03AmoxSDUCartonCSB, dc.quantite04AmoxSDUCartonCSB, dc.dateExpiration04AmoxSDUCartonCSB, dc.totalAmoxSDUCartonCSB,
                    dc.totalAmoxSDUCartonSDSP,
                    dc.sduFiche, dc.nbrTotalCsb, dc.nbrCSBCRENAS, dc.tauxCouvertureCRENAS, dc.nbrCSBCRENASCommande, dc.tauxEnvoiCommandeCSBCRENAS'])
        ->from('App:DataCrenas', 'dc') 
            ->join('dc.User', 'u') 
            ->andWhere('u.id = :userId') 
            ->setParameter('userId', $prmUserId); 
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataGroup = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataGroup = $result[0];
        }
        return $resultDataGroup;
    }

    public function findDataCrenasByUserSupervisor($prmUserId) 
    {
        $queryBuilder = $this->em->createQueryBuilder(); 

        $subquery = $this->em->createQueryBuilder();
        $subquery->select('reg.id')
            ->from('App:User', 'usr')
            ->join('usr.Region', 'reg')
            ->where('usr.id = :prmUserId');  

        $queryBuilder 
        ->select(['dc.id, dc.totalCRENASAnneePrecedent, dc.totalProjeteAnneePrecedent, dc.totalAnneeProjection, dc.resultatAnneePrecedent, dc.resultatAnneeProjection,
                    dc.besoinAPTE, dc.besoinAMOX, dc.besoinFichePatient, dc.besoinRegistre, dc.besoinCarnetRapportCRENAS, 
                    dc.quantite01PnSDUCartonBSD, dc.dateExpiration01PnSDUCartonBSD, dc.quantite02PnSDUCartonBSD, dc.dateExpiration02PnSDUCartonBSD, dc.quantite03PnSDUCartonBSD, dc.dateExpiration03PnSDUCartonBSD, dc.quantite04PnSDUCartonBSD, dc.dateExpiration04PnSDUCartonBSD, dc.totalPnSDUCartonBSD, 
                    dc.quantite01PnSDUCartonCSB, dc.dateExpiration01PnSDUCartonCSB, dc.quantite02PnSDUCartonCSB, dc.dateExpiration02PnSDUCartonCSB, dc.quantite03PnSDUCartonCSB, dc.dateExpiration03PnSDUCartonCSB, dc.quantite04PnSDUCartonCSB, dc.dateExpiration04PnSDUCartonCSB, dc.totalPnSDUCartonCSB,
                    dc.totalPnSDUCartonSDSP,
                    dc.quantite01AmoxSDUCartonBSD, dc.dateExpiration01AmoxSDUCartonBSD, dc.quantite02AmoxSDUCartonBSD, dc.dateExpiration02AmoxSDUCartonBSD, dc.quantite03AmoxSDUCartonBSD, dc.dateExpiration03AmoxSDUCartonBSD, dc.quantite04AmoxSDUCartonBSD, dc.dateExpiration04AmoxSDUCartonBSD, dc.totalAmoxSDUCartonBSD,
                    dc.quantite01AmoxSDUCartonCSB, dc.dateExpiration01AmoxSDUCartonCSB, dc.quantite02AmoxSDUCartonCSB, dc.dateExpiration02AmoxSDUCartonCSB, dc.quantite03AmoxSDUCartonCSB, dc.dateExpiration03AmoxSDUCartonCSB, dc.quantite04AmoxSDUCartonCSB, dc.dateExpiration04AmoxSDUCartonCSB, dc.totalAmoxSDUCartonCSB,
                    dc.totalAmoxSDUCartonSDSP,
                    dc.sduFiche, dc.nbrTotalCsb, dc.nbrCSBCRENAS, dc.tauxCouvertureCRENAS, dc.nbrCSBCRENASCommande, dc.tauxEnvoiCommandeCSBCRENAS,
                    u.id AS userId, u.Nom AS nomUser, u.Prenoms AS prenomUser, u.Telephone AS telephone, 
                    d.id AS districtId, d.Nom AS nomDistrict,
                    r.id AS regionId, r.Nom AS nomRegion,
                    p.id AS provinceId, p.NomFR AS nomProvince'])
        ->from('App:DataCrenas', 'dc') 
            ->join('dc.User', 'u') 
            ->join('u.Region', 'r')
            ->leftJoin('u.District', 'd')
            ->leftJoin('u.Province', 'p')
            ->andWhere($queryBuilder->expr()->eq('u.Region', '(' . $subquery->getDQL(). ')'))  
            ->setParameter('prmUserId', $prmUserId);
            $result = $queryBuilder->getQuery()->getArrayResult(); 
            $resultDataRegion = null;
            if (is_array($result) && count($result) > 0) {
                $resultDataRegion = $result;
            }
        return $resultDataRegion;
    }

    public function findDataCrenasByRegionId($prmRegionId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dc.id, dc.totalCRENASAnneePrecedent, dc.totalProjeteAnneePrecedent, dc.totalAnneeProjection, dc.resultatAnneePrecedent, dc.resultatAnneeProjection,
                    dc.besoinAPTE, dc.besoinAMOX, dc.besoinFichePatient, dc.besoinRegistre, dc.besoinCarnetRapportCRENAS, 
                    dc.quantite01PnSDUCartonBSD, dc.dateExpiration01PnSDUCartonBSD, dc.quantite02PnSDUCartonBSD, dc.dateExpiration02PnSDUCartonBSD, dc.quantite03PnSDUCartonBSD, dc.dateExpiration03PnSDUCartonBSD, dc.quantite04PnSDUCartonBSD, dc.dateExpiration04PnSDUCartonBSD, dc.totalPnSDUCartonBSD, 
                    dc.quantite01PnSDUCartonCSB, dc.dateExpiration01PnSDUCartonCSB, dc.quantite02PnSDUCartonCSB, dc.dateExpiration02PnSDUCartonCSB, dc.quantite03PnSDUCartonCSB, dc.dateExpiration03PnSDUCartonCSB, dc.quantite04PnSDUCartonCSB, dc.dateExpiration04PnSDUCartonCSB, dc.totalPnSDUCartonCSB,
                    dc.totalPnSDUCartonSDSP,
                    dc.quantite01AmoxSDUCartonBSD, dc.dateExpiration01AmoxSDUCartonBSD, dc.quantite02AmoxSDUCartonBSD, dc.dateExpiration02AmoxSDUCartonBSD, dc.quantite03AmoxSDUCartonBSD, dc.dateExpiration03AmoxSDUCartonBSD, dc.quantite04AmoxSDUCartonBSD, dc.dateExpiration04AmoxSDUCartonBSD, dc.totalAmoxSDUCartonBSD,
                    dc.quantite01AmoxSDUCartonCSB, dc.dateExpiration01AmoxSDUCartonCSB, dc.quantite02AmoxSDUCartonCSB, dc.dateExpiration02AmoxSDUCartonCSB, dc.quantite03AmoxSDUCartonCSB, dc.dateExpiration03AmoxSDUCartonCSB, dc.quantite04AmoxSDUCartonCSB, dc.dateExpiration04AmoxSDUCartonCSB, dc.totalAmoxSDUCartonCSB,
                    dc.totalAmoxSDUCartonSDSP,
                    dc.sduFiche, dc.nbrTotalCsb, dc.nbrCSBCRENAS, dc.tauxCouvertureCRENAS, dc.nbrCSBCRENASCommande, dc.tauxEnvoiCommandeCSBCRENAS,
                    u.id AS userId, u.Nom AS nomUser, u.Prenoms AS prenomUser, u.Telephone AS telephone, 
                    d.id AS districtId, d.Nom AS nomDistrict,
                    r.id AS regionId, r.Nom AS nomRegion,
                    p.id AS provinceId, p.NomFR AS nomProvince'])
        ->from('App:DataCrenas', 'dc') 
            ->join('dc.User', 'u') 
            ->leftJoin('u.Region', 'r')
            ->leftJoin('u.District', 'd')
            ->leftJoin('u.Province', 'p')
            ->andWhere('r.id = :prmRegionId')  
            ->setParameter('prmRegionId', $prmRegionId);
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataCrenasRegion = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataCrenasRegion = $result;
        }
        return $resultDataCrenasRegion;
    }

    public function findDataCrenasByDistrictId($prmDistrictId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dc.id, dc.totalCRENASAnneePrecedent, dc.totalProjeteAnneePrecedent, dc.totalAnneeProjection, dc.resultatAnneePrecedent, dc.resultatAnneeProjection,
                    dc.besoinAPTE, dc.besoinAMOX, dc.besoinFichePatient, dc.besoinRegistre, dc.besoinCarnetRapportCRENAS, 
                    dc.quantite01PnSDUCartonBSD, dc.dateExpiration01PnSDUCartonBSD, dc.quantite02PnSDUCartonBSD, dc.dateExpiration02PnSDUCartonBSD, dc.quantite03PnSDUCartonBSD, dc.dateExpiration03PnSDUCartonBSD, dc.quantite04PnSDUCartonBSD, dc.dateExpiration04PnSDUCartonBSD, dc.totalPnSDUCartonBSD, 
                    dc.quantite01PnSDUCartonCSB, dc.dateExpiration01PnSDUCartonCSB, dc.quantite02PnSDUCartonCSB, dc.dateExpiration02PnSDUCartonCSB, dc.quantite03PnSDUCartonCSB, dc.dateExpiration03PnSDUCartonCSB, dc.quantite04PnSDUCartonCSB, dc.dateExpiration04PnSDUCartonCSB, dc.totalPnSDUCartonCSB,
                    dc.totalPnSDUCartonSDSP,
                    dc.quantite01AmoxSDUCartonBSD, dc.dateExpiration01AmoxSDUCartonBSD, dc.quantite02AmoxSDUCartonBSD, dc.dateExpiration02AmoxSDUCartonBSD, dc.quantite03AmoxSDUCartonBSD, dc.dateExpiration03AmoxSDUCartonBSD, dc.quantite04AmoxSDUCartonBSD, dc.dateExpiration04AmoxSDUCartonBSD, dc.totalAmoxSDUCartonBSD,
                    dc.quantite01AmoxSDUCartonCSB, dc.dateExpiration01AmoxSDUCartonCSB, dc.quantite02AmoxSDUCartonCSB, dc.dateExpiration02AmoxSDUCartonCSB, dc.quantite03AmoxSDUCartonCSB, dc.dateExpiration03AmoxSDUCartonCSB, dc.quantite04AmoxSDUCartonCSB, dc.dateExpiration04AmoxSDUCartonCSB, dc.totalAmoxSDUCartonCSB,
                    dc.totalAmoxSDUCartonSDSP,
                    dc.sduFiche, dc.nbrTotalCsb, dc.nbrCSBCRENAS, dc.tauxCouvertureCRENAS, dc.nbrCSBCRENASCommande, dc.tauxEnvoiCommandeCSBCRENAS,
                    u.id AS userId, u.Nom AS nomUser, u.Prenoms AS prenomUser, u.Telephone AS telephone, 
                    d.id AS districtId, d.Nom AS nomDistrict,
                    r.id AS regionId, r.Nom AS nomRegion,
                    p.id AS provinceId, p.NomFR AS nomProvince'])
                    ->from('App:DataCrenas', 'dc') 
                    ->join('dc.User', 'u') 
                    ->join('u.Region', 'r')
                    ->leftJoin('u.District', 'd')
                    ->leftJoin('u.Province', 'p')
                    ->andWhere('d.id = :districtId')  
                    ->setParameter('districtId', $prmDistrictId);
            
                $result = $queryBuilder->getQuery()->getArrayResult(); 
                $resultDataDistrict = null;
            
                if (is_array($result) && count($result) > 0) {
                    $resultDataDistrict = $result;
                }
            
                return $resultDataDistrict;
    } 

    public function findDataCrenasByGroupe($prmGroupeId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('g.id AS idGroupe, 
                g.Nom AS nomGroupe, 
                a.id AS anneeId,
                a.Annee AS annee,
                p.id AS provinceId,
                p.NomFR AS provinceNomFR,
                r.id AS regionId,
                r.Nom AS regionNom')
            ->from('App:Groupe', 'g')
            ->leftJoin('g.Annee', 'a') 
            ->leftJoin('g.Provinces', 'p')
            ->leftJoin('p.regions', 'r')
            ->andWhere('g.id = :prmGroupeId')  
            ->setParameter('prmGroupeId', $prmGroupeId);
            $lstDataCrenas = null;
            $result = $queryBuilder->getQuery()->getArrayResult(); 
            if (is_array($result) && count($result) > 0) {  
                for ($i=0; $i < count($result); $i++) { 
                    $idRegion = $result[$i]["regionId"]; 
                    $dataCrenas = $this->findDataCrenasByRegionId($idRegion); 
                    if ($dataCrenas != null) {
                        $lstDataCrenas[] = $dataCrenas;
                    } 
                } 
            }
        return $lstDataCrenas;
    }
}

?>