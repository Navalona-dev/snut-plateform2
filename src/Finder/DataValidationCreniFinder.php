<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class DataValidationCreniFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    }

    public function findDataValidationCreniByCreniId($prmCreniId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dvc.id, 
            dvc.DataMois01ProjectionEstimatedDistrict, 
            dvc.DataMois02ProjectionEstimatedDistrict, 
            dvc.DataMois03ProjectionEstimatedDistrict, 
            dvc.DataMois04ProjectionEstimatedDistrict, 
            dvc.DataMois05ProjectionEstimatedDistrict, 
            dvc.DataMois06ProjectionEstimatedDistrict, 
            dvc.DataMois01ProjectionEstimatedCentral, 
            dvc.DataMois02ProjectionEstimatedCentral, 
            dvc.DataMois03ProjectionEstimatedCentral, 
            dvc.DataMois04ProjectionEstimatedCentral, 
            dvc.DataMois05ProjectionEstimatedCentral, 
            dvc.DataMois06ProjectionEstimatedCentral, 
            dvc.DataMois01ProjectionValidated,
            dvc.DataMois02ProjectionValidated,
            dvc.DataMois03ProjectionValidated,
            dvc.DataMois04ProjectionValidated,
            dvc.DataMois05ProjectionValidated,
            dvc.DataMois06ProjectionValidated'])
        ->from('App:DataValidationCreni', 'dvc') 
            ->join('dvc.DataCreni', 'dc') 
            ->join('dvc.CreniMoisProjectionsAdmissions', 'cmpa')
            ->andWhere('dc.id = :prmCreniId')  
            ->setParameter('prmCreniId', $prmCreniId);
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataValidationCreni = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataValidationCreni = $result[0];
        }
        return $resultDataValidationCreni;
    }

    public function findDataValidationCreniByCreniIdAndMoisProjection($prmDataCreniId, $prmMoisProjectionId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dvc.id, 
            dvc.DataMois01ProjectionEstimatedDistrict, 
            dvc.DataMois02ProjectionEstimatedDistrict, 
            dvc.DataMois03ProjectionEstimatedDistrict, 
            dvc.DataMois04ProjectionEstimatedDistrict, 
            dvc.DataMois05ProjectionEstimatedDistrict, 
            dvc.DataMois06ProjectionEstimatedDistrict, 
            dvc.DataMois01ProjectionEstimatedCentral, 
            dvc.DataMois02ProjectionEstimatedCentral, 
            dvc.DataMois03ProjectionEstimatedCentral, 
            dvc.DataMois04ProjectionEstimatedCentral, 
            dvc.DataMois05ProjectionEstimatedCentral, 
            dvc.DataMois06ProjectionEstimatedCentral, 
            dvc.DataMois01ProjectionValidated,
            dvc.DataMois02ProjectionValidated,
            dvc.DataMois03ProjectionValidated,
            dvc.DataMois04ProjectionValidated,
            dvc.DataMois05ProjectionValidated,
            dvc.DataMois06ProjectionValidated'])
        ->from('App:DataValidationCreni', 'dvc') 
            ->join('dvc.DataCreni', 'dc') 
            ->join('dvc.CreniMoisProjectionsAdmissions', 'cmpa')
            ->andWhere('dc.id = :dataCreniId') 
            ->andWhere('cmpa.id = :moisProjectionId') 
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