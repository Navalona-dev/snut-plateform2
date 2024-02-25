<?php

namespace App\Finder;

use App\Entity\DataCreni;
use Doctrine\ORM\EntityManagerInterface;

Class DataCreniFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    }

    public function findAllRegionCreni($prmRegionId = "")
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select(
            'r.id AS regionId',
            'r.Nom AS regionNom',
            'r.ChefLieu AS regionChefLieu',
            'COUNT(d.id) AS nombreDeDistricts'
        )
        ->from('App:Region', 'r')
        ->leftJoin('App:District', 'd', 'WITH', 'd.region = r.id')
        ->leftJoin('App:User', 'u', 'WITH', 'u.District = d.id')
        ->where('d.isEligibleForCreni = 1');

        if (!empty($prmRegionId)) {
            $queryBuilder->andWhere('r.id = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }

        $queryBuilder->groupBy('r.id', 'r.Nom', 'r.ChefLieu')
            ->orderBy('r.Nom', 'ASC');

        $query = $queryBuilder->getQuery();
        $resultLstRegionCreni = $query->getArrayResult();
        if (isset($resultLstRegionCreni) && is_array($resultLstRegionCreni) && count($resultLstRegionCreni) > 0) {
            for ($i=0; $i < count($resultLstRegionCreni); $i++) {
                $regionId = $resultLstRegionCreni[$i]["regionId"];
                $nombreCrenis = $this->getNombreCreniFromRegion($regionId);
                $resultLstRegionCreni[$i]["nombreDeCrenis"] = $nombreCrenis;
            }
        } 
        return $resultLstRegionCreni; 
    }

    public function getNombreCreniFromRegion($prmRegionId)
    {
        $query = $this->em->createQuery("
            SELECT 
                COUNT(dc.id) AS nombreCrenis 
            FROM App:DataCreni dc 
            INNER JOIN App:User u WITH u.id = dc.User 
            WHERE u.Region = :prmRegionId
        ") 
        ->setParameter('prmRegionId', $prmRegionId);
        $resultLstRegionCreni = $query->getArrayResult()[0]["nombreCrenis"];
        return $resultLstRegionCreni;
    }

    public function findDataCreniByUserId($prmUserId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dc.id, dc.totalAdmissionCreniSemestrePrecedent, dc.totalAdmissionCreniProjetePrecedent, dc.ResultatDifferenceAdmissionPrecedent, dc.totalAdmissionCreniProjeterProchain, dc.ResultatDifferenceAdmissionProchainPrecedent,
                dc.f75Boites, dc.f100Boites , dc.ReSoMalSachet , dc.pnSachet , dc.ficheSuiviCreni , dc.ficheSuiviIntensif, dc.kitMedicamentsCreni10Patients, dc.registreCreni, dc.carnetRapportMensuelCreni,
                dc.kitCreniAmoxici, 
                dc.kitCreniNystatin, 
                dc.kitCreniFluconazole,
                dc.kitCreniCiprofloxacin,
                dc.kitCreniAmpicillinpdr,
                dc.kitCreniGentamicininj,
                dc.kitCreniSod,
                dc.kitCreniGlucoseInj ,
                dc.kitCreniGlucoseHypertonInj,
                dc.kitCreniFurosemideinj,
                dc.kitCreniChlorhexidine,
                dc.kitCreniMiconazole, 
                dc.kitCreniTetracyclineeyeointment,
                dc.kitCreniTubeFeeding,
                dc.kitCreniTubeFeedingCH05,
                dc.kitCreniSyringeDisp2ml,
                dc.kitCreniSyringeDisp10ml,
                dc.kitCreniSyringeDisp20ml,
                dc.kitCreniSyringeDisp50ml,
                dc.sduF75Boites,
                dc.sduF100Boites,
                dc.sduReSoMal,
                dc.sduPnSachet,
                dc.sduFicheSuiviCreni,
                dc.sduFicheSuiviIntensif,
                dc.sduAmoxiciPdr,
                dc.sduNystatinOral,
                dc.sduFluconazole50mg,
                dc.sduAmpicillinpdrInj500mg,
                dc.sduGentamicininj40mg,
                dc.sduSodLactatInj500ml,
                dc.sduGlucoseInj500ml,
                dc.sduGlucoseHyperton50ml,
                dc.sduFurosemideinj10mg,
                dc.sduChlorhexidineConSol,
                dc.sduMiconazoleNitrate,
                dc.sduTetracyclineeyeointment,
                dc.sduTubeFeedingCH08,
                dc.sduTubeFeedingCH05,
                dc.sduSyringeDisp2ml,
                dc.sduSyringeDisp10ml,
                dc.sduSyringeDisp20ml,
                dc.sduSyringeFeeding50ml,
                dc.sduCiprofloxacin250mg,
                u.id AS userId, u.Nom AS nomUser, u.Prenoms AS prenomUser, u.Telephone AS telephone, 
                d.id AS districtId, d.Nom AS nomDistrict
        '])
        ->from('App:DataCreni', 'dc') 
            ->join('dc.User', 'u') 
            ->leftJoin('u.Region', 'r')
            ->leftJoin('u.District', 'd')
            ->leftJoin('u.Province', 'p')
            ->andWhere('u.id = :userId') 
            ->setParameter('userId', $prmUserId); 
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataGroup = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataGroup = $result[0];
        }
        return $resultDataGroup;
    }

    public function findDataCreniByRegionId($prmRegionId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dc.id, dc.totalAdmissionCreniSemestrePrecedent, dc.totalAdmissionCreniProjetePrecedent, dc.ResultatDifferenceAdmissionPrecedent, dc.totalAdmissionCreniProjeterProchain, dc.ResultatDifferenceAdmissionProchainPrecedent,
                dc.f75Boites, dc.f100Boites , dc.ReSoMalSachet , dc.pnSachet , dc.ficheSuiviCreni , dc.ficheSuiviIntensif, dc.kitMedicamentsCreni10Patients, dc.registreCreni, dc.carnetRapportMensuelCreni,
                dc.kitCreniAmoxici, 
                dc.kitCreniNystatin, 
                dc.kitCreniFluconazole,
                dc.kitCreniCiprofloxacin,
                dc.kitCreniAmpicillinpdr,
                dc.kitCreniGentamicininj,
                dc.kitCreniSod,
                dc.kitCreniGlucoseInj ,
                dc.kitCreniGlucoseHypertonInj,
                dc.kitCreniFurosemideinj,
                dc.kitCreniChlorhexidine,
                dc.kitCreniMiconazole, 
                dc.kitCreniTetracyclineeyeointment,
                dc.kitCreniTubeFeeding,
                dc.kitCreniTubeFeedingCH05,
                dc.kitCreniSyringeDisp2ml,
                dc.kitCreniSyringeDisp10ml,
                dc.kitCreniSyringeDisp20ml,
                dc.kitCreniSyringeDisp50ml,
                dc.sduF75Boites,
                dc.sduF100Boites,
                dc.sduReSoMal,
                dc.sduPnSachet,
                dc.sduFicheSuiviCreni,
                dc.sduFicheSuiviIntensif,
                dc.sduAmoxiciPdr,
                dc.sduNystatinOral,
                dc.sduFluconazole50mg,
                dc.sduAmpicillinpdrInj500mg,
                dc.sduGentamicininj40mg,
                dc.sduSodLactatInj500ml,
                dc.sduGlucoseInj500ml,
                dc.sduGlucoseHyperton50ml,
                dc.sduFurosemideinj10mg,
                dc.sduChlorhexidineConSol,
                dc.sduMiconazoleNitrate,
                dc.sduTetracyclineeyeointment,
                dc.sduTubeFeedingCH08,
                dc.sduTubeFeedingCH05,
                dc.sduSyringeDisp2ml,
                dc.sduSyringeDisp10ml,
                dc.sduSyringeDisp20ml,
                dc.sduSyringeFeeding50ml,
                dc.sduCiprofloxacin250mg,
                u.id AS userId, u.Nom AS nomUser, u.Prenoms AS prenomUser, u.Telephone AS telephone, 
                d.id AS districtId, d.Nom AS nomDistrict,
                r.id AS regionId, r.Nom AS nomRegion,
                p.id AS provinceId, p.NomFR AS nomProvince
        '])
        ->from('App:DataCreni', 'dc') 
            ->join('dc.User', 'u') 
            ->leftJoin('u.Region', 'r')
            ->leftJoin('u.District', 'd')
            ->leftJoin('u.Province', 'p')
            ->andWhere('r.id = :prmRegionId')  
            ->setParameter('prmRegionId', $prmRegionId);
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataCreniRegion = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataCreniRegion = $result;
        }
        return $resultDataCreniRegion;
    }

    public function findListCreniByRegion($prmRegionId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dc.id, dc.totalAdmissionCreniSemestrePrecedent, dc.totalAdmissionCreniProjetePrecedent, dc.ResultatDifferenceAdmissionPrecedent, dc.totalAdmissionCreniProjeterProchain, dc.ResultatDifferenceAdmissionProchainPrecedent,
                dc.f75Boites, dc.f100Boites , dc.ReSoMalSachet , dc.pnSachet , dc.ficheSuiviCreni , dc.ficheSuiviIntensif, dc.kitMedicamentsCreni10Patients, dc.registreCreni, dc.carnetRapportMensuelCreni,
                dc.kitCreniAmoxici, 
                dc.kitCreniNystatin, 
                dc.kitCreniFluconazole,
                dc.kitCreniCiprofloxacin,
                dc.kitCreniAmpicillinpdr,
                dc.kitCreniGentamicininj,
                dc.kitCreniSod,
                dc.kitCreniGlucoseInj ,
                dc.kitCreniGlucoseHypertonInj,
                dc.kitCreniFurosemideinj,
                dc.kitCreniChlorhexidine,
                dc.kitCreniMiconazole, 
                dc.kitCreniTetracyclineeyeointment,
                dc.kitCreniTubeFeeding,
                dc.kitCreniTubeFeedingCH05,
                dc.kitCreniSyringeDisp2ml,
                dc.kitCreniSyringeDisp10ml,
                dc.kitCreniSyringeDisp20ml,
                dc.kitCreniSyringeDisp50ml,
                dc.sduF75Boites,
                dc.sduF100Boites,
                dc.sduReSoMal,
                dc.sduPnSachet,
                dc.sduFicheSuiviCreni,
                dc.sduFicheSuiviIntensif,
                dc.sduAmoxiciPdr,
                dc.sduNystatinOral,
                dc.sduFluconazole50mg,
                dc.sduAmpicillinpdrInj500mg,
                dc.sduGentamicininj40mg,
                dc.sduSodLactatInj500ml,
                dc.sduGlucoseInj500ml,
                dc.sduGlucoseHyperton50ml,
                dc.sduFurosemideinj10mg,
                dc.sduChlorhexidineConSol,
                dc.sduMiconazoleNitrate,
                dc.sduTetracyclineeyeointment,
                dc.sduTubeFeedingCH08,
                dc.sduTubeFeedingCH05,
                dc.sduSyringeDisp2ml,
                dc.sduSyringeDisp10ml,
                dc.sduSyringeDisp20ml,
                dc.sduSyringeFeeding50ml,
                dc.sduCiprofloxacin250mg
        '])
        ->from('App:DataCreni', 'dc') 
            ->join('dc.User', 'u') 
            ->join('u.Region', 'r') 
            ->andWhere('r.id = :prmRegionId') 
            ->setParameter('prmRegionId', $prmRegionId); 
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataCreniRegion = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataCreniRegion = $result;
        }
        return $resultDataCreniRegion;
    }

}

?>