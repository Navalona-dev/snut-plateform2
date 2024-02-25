<?php

namespace App\Finder;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\OrderBy;

class StockDataFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    } 

    public function findAllStockData($prmRegionId = "")
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder
            ->select('sd.id AS stockDataId, 
                sd.nombreCSB, 
                sd.nombreRupture, 
                sd.nombreSoustock, 
                sd.nombreNormal, 
                sd.nombreSurStock,
                rn.id AS rmaNutId, 
                rn.newFileName AS newFileName, 
                rn.originalFileName AS originalFileName,
                rn.uploadedDate AS uploadedDate, 
                u.id AS idUser, 
                u.Nom AS nomUser, 
                u.Prenoms AS prenomUser,
                u.Telephone AS telephoneUser, 
                u.email AS email')
            ->from('App:StockData', 'sd')
            ->leftJoin('sd.rmaNut', 'rn')
            ->leftJoin('rn.uploadedBy', 'u');

        if (!empty($prmRegionId)) {
            $queryBuilder
                ->leftJoin('rn.Region', 'r')
                ->andWhere('r.id = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }

        $queryBuilder
            ->andWhere('rn.newFileName IS NOT NULL')
            ->orderBy('rn.uploadedDate', 'DESC');

        $query = $queryBuilder->getQuery();
        $resultStockData = $query->getArrayResult();

        return $resultStockData;
    } 

    public function findStockDataCountByStatus($prmRegionId = "")
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder
            ->select('r.id AS regionId, 
                r.Nom AS regionNom, 
                d.id AS districtId, 
                d.Nom AS districtNom,
                COUNT(sd.id) AS totalStockData, 
                SUM(sd.nombreCSB) AS totalNombreCSB,
                SUM(sd.nombreRupture) AS totalNombreRupture, 
                SUM(sd.nombreSoustock) AS totalNombreSoustock,
                SUM(sd.nombreNormal) AS totalNombreNormal, 
                SUM(sd.nombreSurStock) AS totalNombreSurStock')
            ->from('App:District', 'd')
            ->leftJoin('d.region', 'r')
            ->leftJoin('App:RmaNut', 'rn', 'WITH', 'rn.District = d.id')
            ->leftJoin('App:StockData', 'sd', 'WITH', 'sd.rmaNut = rn.id');

        if (!empty($prmRegionId)) {
            $queryBuilder
                ->andWhere('r.id = :prmRegionId')
                ->setParameter('prmRegionId', $prmRegionId);
        }

        $queryBuilder
            ->groupBy('r.id, r.Nom, d.id, d.Nom');

        $query = $queryBuilder->getQuery();
        $result = $query->getArrayResult();

        return $result;
    }


}
