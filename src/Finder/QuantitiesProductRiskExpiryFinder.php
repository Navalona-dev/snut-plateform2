<?php

namespace App\Finder;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\OrderBy;

class QuantitiesProductRiskExpiryFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findAllQuantitiesProductRiskExpiries($prmRegionId = "")
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder
            ->select('qp.id AS quantitiesProductRiskExpiryId, 
                qp.PN, 
                qp.AMOXI, 
                qp.F75, 
                qp.F100,
                rn.id AS rmaNutId, 
                rn.newFileName AS newFileName, 
                rn.originalFileName AS originalFileName,
                rn.uploadedDate AS uploadedDate, 
                u.id As idUser, 
                u.Nom AS nomUser, 
                u.Prenoms AS prenomUser,
                u.Telephone AS telephoneUser, 
                u.email AS email')
            ->from('App:QuantitiesProductRiskExpiry', 'qp')
            ->leftJoin('qp.rmaNut', 'rn')
            ->leftJoin('rn.uploadedBy', 'u')
            ->where('rn.newFileName IS NOT NULL')
            ->orderBy('rn.uploadedDate', 'DESC');

        if (!empty($prmRegionId)) {
            $queryBuilder
                ->andWhere('rn.Region = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }

        $query = $queryBuilder->getQuery();
        $resultQuantitiesProductRiskExpiry = $query->getArrayResult();

        return $resultQuantitiesProductRiskExpiry;
    }


    public function findQuantitiesExpiryByDistrictAndRegion($prmRegionId = "")
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder
            ->select('r.id AS regionId, 
                r.Nom AS regionNom, 
                d.id AS districtId, 
                d.Nom AS districtNom,
                SUM(qpe.PN) AS nombreQuantitiesExpiryPn,
                SUM(qpe.F75) AS nombreQuantitiesExpiryF75,
                SUM(qpe.F100) AS nombreQuantitiesExpiryF100')
            ->from('App:QuantitiesProductRiskExpiry', 'qpe')
            ->leftJoin('qpe.rmaNut', 'rn')
            ->leftJoin('rn.District', 'd')
            ->leftJoin('d.region', 'r')
            ->groupBy('r.id, r.Nom, d.id, d.Nom')
            ->having('nombreQuantitiesExpiryPn > 0 OR nombreQuantitiesExpiryF75 > 0 OR nombreQuantitiesExpiryF100 > 0');

        if (!empty($prmRegionId)) {
            $queryBuilder
                ->andWhere('d.region = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }

        $query = $queryBuilder->getQuery();
        $result = $query->getArrayResult();

        return $result;
    }

}
