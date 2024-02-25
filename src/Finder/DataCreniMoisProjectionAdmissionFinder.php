<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class DataCreniMoisProjectionAdmissionFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    } 

    public function findDataCreniMoisProjectionAdmissionByCreniId($prmDataCreniId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dcmpa.id, 
                    dcmpa.DataMois01AdmissionCreniPrecedent, 
                    dcmpa.DataMois02AdmissionCreniPrecedent, 
                    dcmpa.DataMois03AdmissionCreniPrecedent, 
                    dcmpa.DataMois04AdmissionCreniPrecedent, 
                    dcmpa.DataMois05AdmissionCreniPrecedent, 
                    dcmpa.DataMois06AdmissionCreniPrecedent,
                    dcmpa.DataMois01AdmissionProjeteAnneePrecedent, dcmpa.DataMois02AdmissionProjeteAnneePrecedent, dcmpa.DataMois03AdmissionProjeteAnneePrecedent, dcmpa.DataMois04AdmissionProjeteAnneePrecedent, dcmpa.DataMois05AdmissionProjeteAnneePrecedent, dcmpa.DataMois06AdmissionProjeteAnneePrecedent,
                    dcmpa.DataMois01ProjectionAnneePrevisionnelle, dcmpa.DataMois02ProjectionAnneePrevisionnelle, dcmpa.DataMois03ProjectionAnneePrevisionnelle, dcmpa.DataMois04ProjectionAnneePrevisionnelle, dcmpa.DataMois05ProjectionAnneePrevisionnelle, dcmpa.DataMois06ProjectionAnneePrevisionnelle
        '])
        ->from('App:DataCreniMoisProjectionAdmission', 'dcmpa') 
            ->join('dcmpa.DataCreni', 'dc') 
            ->join('dcmpa.CreniMoisProjectionsAdmissions', 'mpa')
            ->andWhere('dc.id = :dataCreniId') 
            ->setParameter('dataCreniId', $prmDataCreniId); 
        $result = $queryBuilder->getQuery()->getArrayResult();  
        return $result;
    }

    public function findDataCreniMoisProjectionAdmissionByCreniIdAndMoisProjection($prmDataCreniId, $prmMoisProjectionId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dcmpa.id, 
                dcmpa.DataMois01AdmissionCreniPrecedent, 
                dcmpa.DataMois02AdmissionCreniPrecedent, 
                dcmpa.DataMois03AdmissionCreniPrecedent, 
                dcmpa.DataMois04AdmissionCreniPrecedent, 
                dcmpa.DataMois05AdmissionCreniPrecedent, 
                dcmpa.DataMois06AdmissionCreniPrecedent,
                dcmpa.DataMois01AdmissionProjeteAnneePrecedent, dcmpa.DataMois02AdmissionProjeteAnneePrecedent, dcmpa.DataMois03AdmissionProjeteAnneePrecedent, dcmpa.DataMois04AdmissionProjeteAnneePrecedent, dcmpa.DataMois05AdmissionProjeteAnneePrecedent, dcmpa.DataMois06AdmissionProjeteAnneePrecedent,
                dcmpa.DataMois01ProjectionAnneePrevisionnelle, dcmpa.DataMois02ProjectionAnneePrevisionnelle, dcmpa.DataMois03ProjectionAnneePrevisionnelle, dcmpa.DataMois04ProjectionAnneePrevisionnelle, dcmpa.DataMois05ProjectionAnneePrevisionnelle, dcmpa.DataMois06ProjectionAnneePrevisionnelle
        '])
        ->from('App:DataCreniMoisProjectionAdmission', 'dcmpa') 
            ->join('dcmpa.DataCreni', 'dc') 
            ->join('dcmpa.CreniMoisProjectionsAdmissions', 'mpa')
            ->andWhere('dc.id = :dataCreniId') 
            ->andWhere('mpa.id = :moisProjectionId') 
            ->setParameter('dataCreniId', $prmDataCreniId)
            ->setParameter('moisProjectionId', $prmMoisProjectionId); 
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataCreni = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataCreni = $result[0];
        }
        return $resultDataCreni;
    }
}

?>