<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\QuantitiesProductRiskExpiry;
use App\Entity\StockData;
use App\Finder\RmaNutFinder;
use App\Repository\RmaNutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class RmanutService
{

    private $_rmaNutFinder;
    private $_rmaNutRepository;

    public function __construct(EntityManagerInterface $em, ManagerRegistry $registry)
    {
        $this->_rmaNutFinder = new RmaNutFinder($em);
        $this->_rmaNutRepository = new RmaNutRepository($registry);
    }

    /**
     * Extraire et persister les données de status de stock pour CRENAS
     */
    public function extractExcelForStockData(EntityManagerInterface $entityManager, $filePath)
    {
        // $entityManager->createQuery('DELETE FROM App\Entity\QuantitiesProductRiskExpiry')->execute(); // Pour faire supprimer les données
        // Tronquer la table StockData avec une requête native
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('stock_data', true /* cascade */, false /* reset */));

            
        $allRmanus = $this->_rmaNutFinder->findAllRmaNut();

        $excelService = new ExcelService();
        $resultats1 = [];
        foreach ($allRmanus as $allRmanu) {
            $fullFilePath = $filePath . $allRmanu["newFileName"];
            $sheetName = 'Niveau de stock PN';
            $startRow = 9;
            $startColumn = 17;
            $endRow = 70;
            $endColumn = 28;

            $resultats1[] = [$allRmanu["rmaNutId"], $excelService->extractDataByColumn($fullFilePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn)];
        }

        // $currentMonthNameArray = [];
        // foreach ($resultats1 as $resultat1) {
        //     if (count($resultat1[1]) > 0) {
        //         $currentMonthData = [];
        //         $currentIdRmanut = $resultat1[0];

        //         // Parcours du tableau depuis la dernière position
        //         for ($i = count($resultat1[1]) - 1; $i >= 0; $i--) {
        //             $compteurValue = 0;

        //             // Parcours du sous-tableau
        //             for ($j = 0; $j < count($resultat1[1][$i]); $j++) {
        //                 // Comptage des éléments non vides
        //                 if ($resultat1[1][$i][$j] != '') {
        //                     $compteurValue++;
        //                 }
        //             }

        //             if ($compteurValue > 2) {
        //                 // sous-tableau avec au moins 2 éléments non vides
        //                 $currentMonthData = [$resultat1[1][$i][0], $resultat1[1][$i]]; // ['Janvier', ['Rupture', 'Sous Stock', 'Normal', 'Surstock']]
        //                 break;
        //             }
        //         }

        //         if (!empty($currentMonthData[1])) {
        //             $currentMonthNameArray[] = $currentMonthData[1][0]; // Accédez directement au mois à l'indice 0 du sous-tableau                   
        //         }

        //         var_dump($resultat1);
        //         var_dump($currentMonthData);
        //         var_dump($currentMonthNameArray);
        //         var_dump($compteurValue);
        //         die();
        
        //     }
        // }

        // $moisWeights = [
        //     'janv' => 1,
        //     'févr' => 2,
        //     'mars' => 3,
        //     'avr'  => 4,
        //     'mai'  => 5,
        //     'juin' => 6,
        //     'juil' => 7,
        //     'août' => 8,
        //     'sept' => 9,
        //     'oct'  => 10,
        //     'nov'  => 11,
        //     'déc'  => 12,
        // ];

        // $maxWeight = 0;
        // $moisPlusEleve = '';

        // foreach ($currentMonthNameArray as $mois) {
        //     $weight = $moisWeights[$mois];
        //     if ($weight > $maxWeight) {
        //         $maxWeight = $weight;
        //         $moisPlusEleve = $mois;
        //     }
        // }

        $ruptureCount = 0;
        $sousStockCount = 0;
        $normalCount = 0;
        $surStockCount = 0;
        foreach ($resultats1 as $resultat1) {
            if (count($resultat1[1]) > 0) {
                $currentMonthData = [];
                $currentIdRmanut = $resultat1[0];

                // Parcours du tableau depuis la dernière position
                for ($i = count($resultat1[1]) - 1; $i >= 0; $i--) {
                    $compteurValue = 0;

                    // Parcours du sous-tableau
                    for ($j = 0; $j < count($resultat1[1][$i]); $j++) {
                        // Comptage des éléments non vides
                        if ($resultat1[1][$i][$j] != '') {
                            $compteurValue++;
                        }
                    }

                    if ($compteurValue > 2) {
                        // sous-tableau avec au moins 2 éléments non vides
                        $currentMonthData = [$resultat1[1][$i][0], $resultat1[1][$i]]; // ['Janvier', ['Rupture', 'Sous Stock', 'Normal', 'Surstock']]
                        break;
                    }
                }

                // Initialisation des compteurs
                $currentRuptureCount = 0;
                $currentSousStockCount = 0;
                $currentNormalCount = 0;
                $currentSurStockCount = 0;
                $testValue = [];
                // Parcours du tableau
                foreach ($currentMonthData as $value) {
                    // Vérifiez si $value est un tableau
                    if (is_array($value)) {
                        foreach ($value as $val) {
                            switch ($val) {
                                case 'Rupture':
                                    $currentRuptureCount++;
                                    break;
                                case 'Sous Stock':
                                    $currentSousStockCount++;
                                    break;
                                case 'Normal':
                                    $currentNormalCount++;
                                    break;
                                case 'Surstock':
                                    $currentSurStockCount++;
                                    break;
                            }
                        }
                    }
                }
                // Stocke le sous-tableau dans $resultats2    
                $currentRmanut = $this->_rmaNutRepository->getById($currentIdRmanut);     
                if ($currentRmanut !== null) {
                    $currentStockData = array(
                        'rmaNut' => $currentRmanut,
                        'ruptureCount' => $currentRuptureCount,
                        'sousStockCount' => $currentSousStockCount,
                        'normalCount' => $currentNormalCount,
                        'surStockCount' => $currentSurStockCount
                    );
    
                    // $resultats2[] = $testValue;
                    $this->persistStockData($entityManager, $currentStockData);
                    $ruptureCount += $currentRuptureCount;
                    $sousStockCount += $currentSousStockCount;
                    $normalCount += $currentNormalCount;
                    $surStockCount += $currentSurStockCount;
                }
            }
        }

        $resultatsStockData = array(
            'ruptureCount' => $ruptureCount,
            'sousStockCount' => $sousStockCount,
            'normalCount' => $normalCount,
            'surStockCount' => $surStockCount
        );

        return $resultatsStockData;
    }

    /**
     * Extraire et persister les données de quantité à expirer d'ici 3 mois.
     */
    public function extractExcelForQuantitiesProductRiskExpiry(EntityManagerInterface $entityManager, $filePath)
    {
        // Tronquer la table StockData avec une requête native
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('quantities_product_risk_expiry', true /* cascade */, false /* reset */));

        $allRmanus = $this->_rmaNutFinder->findAllRmaNut();
        $excelService = new ExcelService();
        $sheetName = 'CANEVAS SDSP';
        $resultatsF100 = 0;
        $resultatsF75 = 0;
        $resultatsPN = 0;
        $resultatsRiskExpiry = [];
        foreach ($allRmanus as $allRmanu) {
            $fullFilePath = $filePath . $allRmanu["newFileName"];

            // PN (Plumpy Nut)
            $startRow = 24;
            $startColumn = 19;
            $endRow = 35;
            $endColumn = 19;
            $currentPN = $excelService->extractDataByColumn($fullFilePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
            $currentNbRiskExpiryPN = 0;
            if (!empty($currentPN[0]) and count($currentPN[0])) {
                // foreach ($currentPN[0] as $valuePN) {
                //     if ($valuePN > 0) {
                //         $currentNbRiskExpiryPN += $valuePN;
                //     }
                //     // var_dump($valuePN);
                // }
                // // die();
                $currentNbRiskExpiryPN = !empty($currentPN[0][8]) ? $currentPN[0][8] : 0;
            }

            // F75
            $startRow = 58;
            $startColumn = 18;
            $endRow = 69;
            $endColumn = 18;
            $currentF75 = $excelService->extractDataByColumn($fullFilePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
            $currentNbRiskExpiryF75 = 0;
            if (!empty($currentF75[0]) and count($currentF75[0])) {
                // foreach ($currentF75[0] as $valueF75) {
                //     if ($valueF75 > 0) {
                //         $currentNbRiskExpiryF75 += $valueF75;
                //     }
                //     // var_dump($valueF75);
                // }
                // die();

                $currentNbRiskExpiryF75 = !empty($currentF75[0][8]) ? $currentF75[0][8] : 0;
            }

            // F100
            $startRow = 58;
            $startColumn = 7;
            $endRow = 69;
            $endColumn = 7;
            $current100 = $excelService->extractDataByColumn($fullFilePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
            $currentNbRiskExpiryF100 = 0;
            if (!empty($current100[0]) and count($current100[0])) {
                // foreach ($current100[0] as $valueF100) {
                //     if ($valueF100 > 0) {
                //         $currentNbRiskExpiryF100 += $valueF100;
                //     }
                //     // var_dump($valueF100);
                // }
                // // die();
                $currentNbRiskExpiryF100 = !empty($current100[0][8]) ? $current100[0][8] : 0;
            }

            $currentRmanut = $this->_rmaNutRepository->getById($allRmanu['rmaNutId']);
            $quantitiesProductRiskExpiry = array(
                "RMANUT" =>  $currentRmanut,
                "PN" =>  $currentNbRiskExpiryPN,
                "F75" =>  $currentNbRiskExpiryF75,
                "F100" =>  $currentNbRiskExpiryF100
            );
            $this->persistQuantitiesProductRiskExpiry($entityManager, $quantitiesProductRiskExpiry);
            $resultatsPN += (!empty($currentNbRiskExpiryPN) and is_numeric($currentNbRiskExpiryPN) ? $currentNbRiskExpiryPN : 0);
            $resultatsF75 += (!empty($currentNbRiskExpiryF75) and is_numeric($currentNbRiskExpiryF75) ? $currentNbRiskExpiryF75 : 0);
            $resultatsF100 += (!empty($currentNbRiskExpiryF100) and is_numeric($currentNbRiskExpiryF100) ? $currentNbRiskExpiryF100 : 0);
        }

        $resultatsRiskExpiry = [
            "F100" => $resultatsF100,
            "F75" => $resultatsF75,
            "PN" => $resultatsPN
        ];

        return $resultatsRiskExpiry;
    }

    // Fonction pour persiter dans la table stock_data
    public function persistStockData(EntityManagerInterface $entityManager, $prmStockData)
    {
        $stockData = new StockData();
        $stockData->setNombreRupture($prmStockData["ruptureCount"]);
        $stockData->setNombreSoustock($prmStockData["sousStockCount"]);
        $stockData->setNombreNormal($prmStockData["normalCount"]);
        $stockData->setNombreSurStock($prmStockData["surStockCount"]);
        $stockData->setRmaNut($prmStockData["rmaNut"]);

        $entityManager->persist($stockData);
        $entityManager->flush();
        return $stockData;
    }

    // Fonction pour persiter dans la table quantities_product_risk_expiry
    public function persistQuantitiesProductRiskExpiry(EntityManagerInterface $entityManager, $prmQuantitiesProductRiskExpiry)
    {
        $quantitiesProductRiskExpiry = new QuantitiesProductRiskExpiry();
        $quantitiesProductRiskExpiry->setPN($prmQuantitiesProductRiskExpiry["PN"]);
        $quantitiesProductRiskExpiry->setF75($prmQuantitiesProductRiskExpiry["F75"]);
        $quantitiesProductRiskExpiry->setF100($prmQuantitiesProductRiskExpiry["F100"]);
        $quantitiesProductRiskExpiry->setRmaNut($prmQuantitiesProductRiskExpiry["RMANUT"]);

        $entityManager->persist($quantitiesProductRiskExpiry);
        $entityManager->flush();
        return $quantitiesProductRiskExpiry;
    }

    // Extraire les CSB en tant qu'array
    public function extractCsb($fullFilePath)
    {
        $sheetName = "GESTION PN CSB";
        $startRow = 11;
        $startColumn = 5;
        $endRow = 120;
        $endColumn = 5;

        $excelService = new ExcelService();
        $csb = [];
        $results = [];
        try {
            $results = $excelService->extractDataByColumn($fullFilePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
        } catch (\Throwable $th) {
            //throw $th;
        }
        if (count($results[0]) > 0) {
            foreach ($results[0] as $value) {
                if ($value !== "" and $value !== 0) {
                    $csb[] = $value;
                }
            }    
        }
       
        return $csb;
    }

    // Fonction pour comparer les mois (vous pouvez ajuster cette fonction selon vos besoins)
    function compareMois($mois1, $mois2)
    {
        $moisOrdre = ['janv', 'févr', 'mars', 'avr', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc'];

        $indexMois1 = array_search($mois1, $moisOrdre);
        $indexMois2 = array_search($mois2, $moisOrdre);

        return $indexMois1 - $indexMois2;
    }
}
