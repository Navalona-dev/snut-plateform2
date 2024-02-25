<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class DataValidationCrenasFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    }

    public function findDataValidationCrenasByCrenasId($prmCrenasId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dvc.id, 
            dvc.EstimatedDataMonthDistrict, 
            dvc.EstimatedDataMonthCentral, 
            dvc.ValidatedDataMonth'])
        ->from('App:DataValidationCrenas', 'dvc') 
            ->join('dvc.DataCrenas', 'dc') 
            ->join('dvc.MoisProjectionsAdmissions', 'mpa')
            ->andWhere('dc.id = :prmCrenasId')  
            ->setParameter('prmCrenasId', $prmCrenasId);
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataValidationCrenas = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataValidationCrenas = $result;
        }
        return $resultDataValidationCrenas;
    }

    public function findDataValidationCrenasByCrenasIdAndMoisProjection($prmDataCrenasId, $prmMoisProjectionId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
        ->select(['dvc.id, 
            dvc.EstimatedDataMonthDistrict, 
            dvc.EstimatedDataMonthCentral, 
            dvc.ValidatedDataMonth'])
        ->from('App:DataValidationCrenas', 'dvc') 
            ->join('dvc.DataCrenas', 'dc') 
            ->join('dvc.MoisProjectionsAdmissions', 'mpa')
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
}