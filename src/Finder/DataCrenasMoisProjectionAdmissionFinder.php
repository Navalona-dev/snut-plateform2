<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class DataCrenasMoisProjectionAdmissionFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    } 

    
    public function findDataCrenasMoisProjectionAdmissionByCrenasId($prmDataCrenasId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dcmpa.id, dcmpa.DataMoisAdmissionCRENASAnneePrecedent, dcmpa.DataMoisAdmissionProjeteAnneePrecedent, dcmpa.DataMoisProjectionAnneePrevisionnelle'])
        ->from('App:DataCrenasMoisProjectionAdmission', 'dcmpa') 
            ->join('dcmpa.DataCrenas', 'dc') 
            ->join('dcmpa.MoisProjectionsAdmissions', 'mpa')
            ->andWhere('dc.id = :dataCrenasId') 
            ->setParameter('dataCrenasId', $prmDataCrenasId); 
        $result = $queryBuilder->getQuery()->getArrayResult();  
        return $result;
    }

    public function findDataCrenasMoisPrevisionnelleAdmissionByCrenasId($prmDataCrenasId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dcmpa.id, dcmpa.DataMoisAdmissionCRENASAnneePrecedent, dcmpa.DataMoisAdmissionProjeteAnneePrecedent, dcmpa.DataMoisProjectionAnneePrevisionnelle'])
        ->from('App:DataCrenasMoisProjectionAdmission', 'dcmpa') 
            ->join('dcmpa.DataCrenas', 'dc') 
            ->join('dcmpa.moisPrevisionnelleEnclave', 'mp')
            ->andWhere('dc.id = :dataCrenasId') 
            ->setParameter('dataCrenasId', $prmDataCrenasId); 
        $result = $queryBuilder->getQuery()->getArrayResult();  
        return $result;
    }

    public function findDataCrenasMoisProjectionAdmissionByCrenasIdAndMoisProjection($prmDataCrenasId, $prmMoisProjectionId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dcmpa.id, dcmpa.DataMoisAdmissionCRENASAnneePrecedent, dcmpa.DataMoisAdmissionProjeteAnneePrecedent, dcmpa.DataMoisProjectionAnneePrevisionnelle'])
        ->from('App:DataCrenasMoisProjectionAdmission', 'dcmpa') 
            ->join('dcmpa.DataCrenas', 'dc') 
            ->join('dcmpa.MoisProjectionsAdmissions', 'mpa')
            ->andWhere('dc.id = :dataCrenasId') 
            ->andWhere('mpa.id = :moisProjectionId') 
            ->setParameter('dataCrenasId', $prmDataCrenasId)
            ->setParameter('moisProjectionId', $prmMoisProjectionId); 
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataCrenas = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataCrenas = $result[0];
        }
        return $resultDataCrenas;
    }

    public function findDataCrenasMoisPrevisionnelleAdmissionByCrenasIdAndMoisProjection($prmDataCrenasId, $prmMoisPrevisionnelleId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dcmpa.id, dcmpa.DataMoisAdmissionCRENASAnneePrecedent, dcmpa.DataMoisAdmissionProjeteAnneePrecedent, dcmpa.DataMoisProjectionAnneePrevisionnelle'])
        ->from('App:DataCrenasMoisProjectionAdmission', 'dcmpa') 
            ->join('dcmpa.DataCrenas', 'dc') 
            ->join('dcmpa.moisPrevisionnelleEnclave', 'mp')
            ->andWhere('dc.id = :dataCrenasId') 
            ->andWhere('mp.id = :moisProjectionId') 
            ->setParameter('dataCrenasId', $prmDataCrenasId)
            ->setParameter('moisProjectionId', $prmMoisPrevisionnelleId); 
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataCrenas = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataCrenas = $result[0];
        }
        return $resultDataCrenas;
    }
}

?>