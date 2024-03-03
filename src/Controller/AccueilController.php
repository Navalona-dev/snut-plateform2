<?php

namespace App\Controller;

use App\Entity\Region;
use App\Entity\RmaNut;
use App\Entity\StockData;
use App\Entity\QuantitiesProductRiskExpiry;
use App\Entity\Message;
use App\Entity\PieceJointe;
use App\Entity\User;
use App\Finder\AnneePrevisionelleFinder;
use App\Finder\CommandeSemestrielleFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Finder\UserFinder;
use App\Finder\GroupeFinder;
use App\Finder\CommandeTrimestrielleFinder;
use App\Finder\CreniMoisProjectionAdmissionFinder;
use App\Finder\DataCrenasFinder;
use App\Finder\DataCrenasMoisProjectionAdmissionFinder;
use App\Finder\DataCreniFinder;
use App\Finder\DataCreniMoisProjectionAdmissionFinder;
use App\Finder\MoisProjectionAdmissionFinder;
use App\Finder\PvrdFinder;
use App\Finder\RmaNutFinder;
use App\Finder\StockDataFinder;
use App\Finder\QuantitiesProductRiskExpiryFinder;
use App\Finder\MessageFinder;
use App\Repository\CommandeTrimestrielleRepository;
use App\Repository\RmaNutRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ExcelService;
use App\Service\RmanutService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AccueilController extends AbstractController
{

    private $_userService;
    private $_commandeTrimestrielleService;
    private $_commandeSemestrielleService;
    private $_rmaNutService;
    private $_stockDataService;
    private $_quantitiesProductRiskExpiryService;
    private $_messageService;
    private $_rmaNutRepository;
    private $_dataCrenaService;
    private $_dataCreniService;
    private $_groupeService;
    private $_moisProjectionAdmissionService;
    private $_anneePrevisionnelleService;
    private $_dataCrenasMoisProjectionAdmission;
    private $_creniMoisProjectionAdmissionService;
    private $_dataCreniMoisProjectionAdmissionService;
    private $_pvrdService;

    public function __construct(
        UserFinder $user_service_container,
        RmaNutFinder $rma_nut_container,
        StockDataFinder $stock_data_container,
        QuantitiesProductRiskExpiryFinder $quantities_product_risk_expiry_container,
        MessageFinder $message_container,
        CommandeTrimestrielleFinder $commande_trimetrielle_container,
        CommandeSemestrielleFinder $commande_semestrielle_container,
        DataCrenasFinder $data_crena_container,
        DataCreniFinder $data_creni_container,
        CreniMoisProjectionAdmissionFinder $creni_mois_projection_admission_container,
        GroupeFinder $groupe_container,
        MoisProjectionAdmissionFinder $mois_projection_admission_container,
        AnneePrevisionelleFinder $annee_previonnelle_container,
        DataCrenasMoisProjectionAdmissionFinder $data_crenas_mois_projection_admission,
        DataCreniMoisProjectionAdmissionFinder $data_creni_mois_projection_admission_container,
        PvrdFinder $pvrd_service_container,
        RmaNutRepository $rmaNutRepository
    ) {
        $this->_userService = $user_service_container;
        $this->_commandeTrimestrielleService = $commande_trimetrielle_container;
        $this->_commandeSemestrielleService = $commande_semestrielle_container;
        $this->_rmaNutService = $rma_nut_container;
        $this->_stockDataService = $stock_data_container;
        $this->_quantitiesProductRiskExpiryService = $quantities_product_risk_expiry_container;
        $this->_messageService = $message_container;
        $this->_rmaNutRepository = $rmaNutRepository;
        $this->_dataCrenaService = $data_crena_container;
        $this->_dataCreniService = $data_creni_container;
        $this->_groupeService = $groupe_container;
        $this->_moisProjectionAdmissionService = $mois_projection_admission_container;
        $this->_anneePrevisionnelleService = $annee_previonnelle_container;
        $this->_dataCrenasMoisProjectionAdmission = $data_crenas_mois_projection_admission;
        $this->_dataCreniMoisProjectionAdmissionService = $data_creni_mois_projection_admission_container;
        $this->_creniMoisProjectionAdmissionService = $creni_mois_projection_admission_container;
        $this->_pvrdService = $pvrd_service_container;
    }

    #[Route('/test/extract', name: 'app_sp_accueil_test_extract')]
    public function home(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);

            $excelService = new ExcelService();
            $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/RMANut/RMA_NUT_T1_20231120_RATODIFAITRA_mahajanga ii.xlsx';
            // $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/RMANut/aaatest_extract.xlsx';
            if (file_exists($filePath)) {
                // $sheetName = 'CANEVAS SDSP';
                // $startRow = 26;
                // $startColumn = 1;
                // $endRow = 38;
                // $endColumn = 11;
                $sheetName = 'Niveau de stock PN';
                $startRow = 9;
                $startColumn = 17;
                $endRow = 30;
                $endColumn = 28;


                // $result = $excelService->extractData($filePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
                $result2 = $excelService->extractDataByColumn($filePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);

                // Afficher le résultat
                print_r("debut");

                // var_dump($result);
                print_r("\nFin 1\n");
                var_dump($result2);
            } else {
                echo "Le fichier n'existe pas.";
            }

            die("Fin du script");
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }

        return $this->render('home.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    #[Route('/', name: 'app_accueil')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CENTRAL_SUPERVISOR') || $this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {

                // 
                if ($this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {
                    $allStockDatas = $this->_stockDataService->findAllStockData($dataUser["idRegion"]);
                    $allStockDatasByStatus = $this->_stockDataService->findStockDataCountByStatus($dataUser["idRegion"]);
                    $allQuantitiesExpiry = $this->_quantitiesProductRiskExpiryService->findQuantitiesExpiryByDistrictAndRegion($dataUser["idRegion"]);
                    $allQuantitiesProductRiskExpiries = $this->_quantitiesProductRiskExpiryService->findAllQuantitiesProductRiskExpiries($dataUser["idRegion"]);
                    $allTauxPvrd = $this->_pvrdService->findAllTauxPvrd($dataUser["idRegion"]);
                } else {
                    $allStockDatas = $this->_stockDataService->findAllStockData();
                    $allStockDatasByStatus = $this->_stockDataService->findStockDataCountByStatus();
                    $allQuantitiesExpiry = $this->_quantitiesProductRiskExpiryService->findQuantitiesExpiryByDistrictAndRegion();
                    $allQuantitiesProductRiskExpiries = $this->_quantitiesProductRiskExpiryService->findAllQuantitiesProductRiskExpiries();
                    $allTauxPvrd = $this->_pvrdService->findAllTauxPvrd();
                }

                $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
                $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
                $AllDistrictAgents = $this->_userService->findAllDistrictAgents();


                $nombreNormal = 0;
                $nombreSousStock = 0;
                $nombreRupture = 0;
                $nombreSurStock = 0;
                foreach ($allStockDatas as $key => $allStockData) {
                    $nombreNormal += $allStockData["nombreNormal"];
                    $nombreSousStock += $allStockData["nombreSoustock"];
                    $nombreRupture += $allStockData["nombreRupture"];
                    $nombreSurStock += $allStockData["nombreSurStock"];
                }

                $nombreallStockDatas = $nombreNormal + $nombreSousStock + $nombreRupture + $nombreSurStock;
                if ($nombreallStockDatas > 0) {
                    $pourcentageNormal = number_format(($nombreNormal / $nombreallStockDatas) * 100, 2);
                    $pourcentageSousStock = number_format(($nombreSousStock / $nombreallStockDatas) * 100, 2);
                    $pourcentageRupture = number_format(($nombreRupture / $nombreallStockDatas) * 100, 2);
                    $pourcentageSurStock = number_format(($nombreSurStock / $nombreallStockDatas) * 100, 2);
                } else {
                    $pourcentageNormal = 0;
                    $pourcentageSousStock = 0;
                    $pourcentageRupture = 0;
                    $pourcentageSurStock = 0;
                }

                // QUANTITE A EXPIRER D'ICI 3 MOIS 
                $quantitiesProductRiskExpiryPN = 0;
                $quantitiesProductRiskExpiryF75 = 0;
                $quantitiesProductRiskExpiryF100 = 0;
                foreach ($allQuantitiesProductRiskExpiries as $key => $quantitiesProductRiskExpiry) {
                    $quantitiesProductRiskExpiryPN += !empty($quantitiesProductRiskExpiry["PN"]) ? $quantitiesProductRiskExpiry["PN"] : 0;
                    $quantitiesProductRiskExpiryF75 += !empty($quantitiesProductRiskExpiry["F75"]) ? $quantitiesProductRiskExpiry["F75"] : 0;
                    $quantitiesProductRiskExpiryF100 += !empty($quantitiesProductRiskExpiry["F100"]) ? $quantitiesProductRiskExpiry["F100"] : 0;
                }

                $sentMessages = $this->_messageService->findAllMessagebySender($user);
                $receivedMessages = $this->_messageService->findAllMessagebyRecipient($user);
                $unreadMessageCount = $entityManager->getRepository(Message::class)->count([
                    'recipient' => $user,
                    'isRead' => false,
                    'isDeletedPerRecipient' => null,
                ]);

                return $this->render('supervisor/dashboardHome.html.twig', [
                    'controller_name' => 'AccueilController',
                    'mnuActive' => 'Home1',
                    'pourcentagePNNormal' => $pourcentageNormal,
                    'pourcentagePNSousStock' => $pourcentageSousStock,
                    'pourcentagePNRupture' => $pourcentageRupture,
                    'pourcentagePNSurStock' => $pourcentageSurStock,
                    'quantitiesProductRiskExpiryPN' => $quantitiesProductRiskExpiryPN,
                    'quantitiesProductRiskExpiryF75' => $quantitiesProductRiskExpiryF75,
                    'quantitiesProductRiskExpiryF100' => $quantitiesProductRiskExpiryF100,
                    'allStockDatasByStatus' => $allStockDatasByStatus,
                    'allQuantitiesExpiry' => $allQuantitiesExpiry,
                    'allCentralSupervisor' => $AllCentralSupervisor,
                    'allRegionalSupervisors' => $AllRegionalSupervisors,
                    'allDistrictAgents' => $AllDistrictAgents,
                    'allTauxPvrd' => $allTauxPvrd,
                    'sentMessages' => $sentMessages,
                    'receivedMessages' => $receivedMessages,
                    'unreadMessageCount' => $unreadMessageCount
                ]);
            } else {
                $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
                $dataRMANut = $this->_rmaNutService->findDataRmaNutByUserCommandeTrimestrielle($userId, $dataCommandeTrimestrielle['idCommandeTrimestrielle']);
                $dataCrenas = $this->_dataCrenaService->findDataCrenasByUserId($userId);
                $dataCreni = $this->_dataCreniService->findDataCreniByUserId($userId);
                $isUserHavingDataCrenas = false;
                if (isset($dataCrenas) && is_array($dataCrenas) && count($dataCrenas) > 0) {
                    $isUserHavingDataCrenas = true;
                }
                $isUserHavingDataCreni = false;
                if (isset($dataCreni) && is_array($dataCreni) && count($dataCreni) > 0) {
                    $isUserHavingDataCreni = true;
                }
                $dataPvrd = $this->_pvrdService->findDataPvrdByUserCommandeTrimestrielle($userId, 1, null);

                return $this->render('home.html.twig', [
                    'controller_name' => 'RmaNutController',
                    "dataUser" => $dataUser,
                    "dataPvrd" => $dataPvrd,
                    "dataRMANut" => $dataRMANut,
                    "isUserHavingDataCrenas" => $isUserHavingDataCrenas,
                    "isUserHavingDataCreni" => $isUserHavingDataCreni
                ]);
            }
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }

    #[Route('/dashboard-rma-nut', name: 'app_dashboard_RmaNut')]
    public function index1(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            //dump($dataUser); dd(); 
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CENTRAL_SUPERVISOR') || $this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {

                $dataAnneePrevisionnelle = $this->_anneePrevisionnelleService->findDataAnneePrevisionnelle();
                $lstGroupe = $this->_groupeService->findAllDataGroupe($dataAnneePrevisionnelle["IdAnneePrevisionnelle"]);
                $lstGroupData = [];
                if (isset($lstGroupe) && is_array($lstGroupe) && count($lstGroupe) > 0) {
                    for ($i = 0; $i < count($lstGroupe); $i++) {
                        $lstGroupData[$lstGroupe[$i]["nomGroupe"]][] = $lstGroupe[$i];
                    }
                }

                // TABLEAU DE BORD 
                if ($this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {
                    $numberDistrict = count($this->_rmaNutService->FindAllDistrictByRegionId($dataUser["idRegion"]));
                    $rapportsAttendus = count($this->_rmaNutService->FindAllDistrictByRegionId($dataUser["idRegion"]));
                    $rapportsParvenus = count($this->_rmaNutService->FindAllDistrictWithRmaNut($dataUser["idRegion"]));
                    $rapportsParvenusData = $this->_rmaNutService->FindAllDistrictWithRmaNut($dataUser["idRegion"]);
                    $lstDistrictWithDetails = $this->_rmaNutService->FindAllDistrictWithDetails($dataUser["idRegion"]);
                } else {
                    $numberDistrict = count($this->_rmaNutService->FindAllDistrict());
                    $rapportsAttendus = count($this->_rmaNutService->FindAllDistrict());
                    $rapportsParvenus = count($this->_rmaNutService->FindAllDistrictWithRmaNut());
                    $rapportsParvenusData = $this->_rmaNutService->FindAllDistrictWithRmaNut();
                    $lstDistrictWithDetails = $this->_rmaNutService->FindAllDistrictWithDetails();
                }

                $tauxRapportage = ($rapportsAttendus > 0) ? round(($rapportsParvenus / $rapportsAttendus) * 100, 2) : 0;

                // Obtenez les dates de début et de fin de la commande trimestrielle
                $commandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
                $dateDebut = $commandeTrimestrielle['dateDebutCommande'];
                $dateFin = $commandeTrimestrielle['dateFinCommande'];

                // Obtenez les RmaNut téléversés entre les deux dates dans la commande trimestrielle
                $rmaNutsTeleverses = $this->_rmaNutService->findRmaNutsBetweenDates($dateDebut, $dateFin);

                // Obtenez le nombre total de RmaNut dans la commande trimestrielle
                $nombreTotalRmaNut = count($this->_rmaNutService->FindAllDistrictWithRmaNut());

                // Obtenez le nombre de RmaNut entre les deux dates
                $nombreRmaNutEntreDates = count($rmaNutsTeleverses);

                // Calculer le taux de promptitude
                $tauxProptitude = ($nombreTotalRmaNut > 0) ? round(($nombreRmaNutEntreDates / $nombreTotalRmaNut) * 100, 2) : 0;

                return $this->render('supervisor/supervisorCentralHome.html.twig', [
                    "mnuActive" => "Home",
                    "dataUser" => $dataUser,
                    "lstGroupData" => $lstGroupData,
                    "RapportParvenusData" => $rapportsParvenusData,
                    "LstDistrictWithDetails" => $lstDistrictWithDetails,
                    "NumberDistrict" => $numberDistrict,
                    "RapportAttendus" => $rapportsAttendus,
                    "RapportParvenus" => $rapportsParvenus,
                    "TauxRapportage" => $tauxRapportage,
                    "TauxProptitude" => $tauxProptitude
                ]);
            } else {
                return $this->render('home.html.twig', [
                    'controller_name' => 'AccueilController',
                ]);
            }
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }


    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        // Ajoutez ici le code spécifique à votre tableau de bord
        $numberDistrict = count($this->_rmaNutService->FindAllDistrict());
        $rapportsAttendus = count($this->_rmaNutService->FindAllDistrict());
        $rapportsParvenus = count($this->_rmaNutService->FindAllDistrictWithRmaNut());
        $rapportsParvenusData = $this->_rmaNutService->FindAllDistrictWithRmaNut();
        $tauxRapportage = ($rapportsAttendus > 0) ? round(($rapportsParvenus / $rapportsAttendus) * 100, 2) : 0;

        // Obtenez les dates de début et de fin de la commande trimestrielle
        $commandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
        $dateDebut = $commandeTrimestrielle['dateDebutCommande'];
        $dateFin = $commandeTrimestrielle['dateFinCommande'];

        // Obtenez les RmaNut téléversés entre les deux dates dans la commande trimestrielle
        $rmaNutsTeleverses = $this->_rmaNutService->findRmaNutsBetweenDates($dateDebut, $dateFin);

        // Obtenez le nombre total de RmaNut dans la commande trimestrielle
        $nombreTotalRmaNut = count($this->_rmaNutService->FindAllDistrictWithRmaNut());

        // Obtenez le nombre de RmaNut entre les deux dates
        $nombreRmaNutEntreDates = count($rmaNutsTeleverses);

        // Calculer le taux de promptitude
        $tauxProptitude = ($nombreTotalRmaNut > 0) ? round(($nombreRmaNutEntreDates / $nombreTotalRmaNut) * 100, 2) : 0;

        return $this->render('dashboard.html.twig', [
            'controller_name' => 'RmaNutController',
            "RapportParvenusData" => $rapportsParvenusData,
            "NumberDistrict" => $numberDistrict,
            "RapportAttendus" => $rapportsAttendus,
            "RapportParvenus" => $rapportsParvenus,
            "TauxRapportage" => $tauxRapportage,
            "TauxProptitude" => $tauxProptitude
        ]);
    }

    #[Route('/dashboard/export', name: 'app_dashboard_export', methods: ['GET', 'POST'])]
    public function dashboardExport(Request $request)
    {
        if ($request->isMethod('POST')) {

            // Ajoutez ici le code spécifique à votre tableau de bord
            $numberDistrict = $request->request->get('hidNumberDistrict');
            $rapportsAttendus = $request->request->get('hidRapportAttendus');
            $rapportsParvenus = $request->request->get('hidRapportParvenus');
            $tauxRapportage = $request->request->get('hidTauxRapportage');
            $tauxProptitude = $request->request->get('hidTauxProptitude');

            // Créez une instance de PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $col = "B";
            $row = 1;
            $sheet->setCellValue($col . $row, "Date");
            $sheet->setCellValue($col . ($row + 1), "Nombre de district");
            $sheet->setCellValue($col . ($row + 2), "Nombre de rapports attendus");
            $sheet->setCellValue($col . ($row + 3), "Nombre de rapports parvenus");
            $sheet->setCellValue($col . ($row + 4), "Taux de rapport");
            $sheet->setCellValue($col . ($row + 5), "Taux de proptitude");

            $col = "C";
            $row = 1;
            $sheet->setCellValue($col . $row, date('Y-m-d H:i:s'));
            $sheet->setCellValue($col . ($row + 1), $numberDistrict);
            $sheet->setCellValue($col . ($row + 2), $rapportsAttendus);
            $sheet->setCellValue($col . ($row + 3), $rapportsParvenus);
            $sheet->setCellValue($col . ($row + 4), $tauxRapportage . "%");
            $sheet->setCellValue($col . ($row + 5), $tauxProptitude . "%");

            $col = "A";
            $row = 8;
            $sheet->setCellValue($col . $row, "PROVINCES");

            $col = "B";
            $row = 8;
            $sheet->setCellValue($col . $row, "REGIONS");

            $col = "C";
            $row = 8;
            $sheet->setCellValue($col . $row, "DISTRICTS");

            $col = "D";
            $row = 8;
            $sheet->setCellValue($col . $row, "PROMPTITUDE");

            $col = "E";
            $row = 8;
            $sheet->setCellValue($col . $row, "RMA Snut");

            $row = 7;
            $rowspan3 = $row + 2;

            $col = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row . ':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "COMPLETUDE");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = "E";
            $row = 8;
            $sheet->setCellValue($col . $row, "BC CRENAS");
            $col = "F";
            $row = 8;
            $sheet->setCellValue($col . $row, "BC CRENI");
            $col = "G";
            $row = 8;
            $sheet->setCellValue($col . $row, "POURCENTAGE COMPLETUDE");

            $writer = new Xlsx($spreadsheet);

            $fileName = "Tableau_de_bord_" . date('d-m-Y Hi') . '.xlsx';

            // Chemin temporaire pour sauvegarder le fichier Excel
            $tempFilePath = sys_get_temp_dir() . '/' . $fileName;

            // Sauvegarder le fichier Excel sur le serveur
            $writer->save($tempFilePath);

            // Retourner le fichier en téléchargement
            return $this->file($tempFilePath, $fileName);
        }
    }

    #[Route('/sendmessage/{role}', name: 'app_dashboard1_retroinfo', methods: ['GET', 'POST'])]
    public function sendmessage(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            if ($request->isMethod('POST')) {
                $role = $request->get('role');
                $isAllCentral = $request->request->get('chkAllCentral');
                $isAllRNR = $request->request->get('chkAllRnr');
                $isAllRND = $request->request->get('chkAllRnd');
                $selectCentral = $request->request->get('selectCentral');
                $selectRnr = $request->request->get('selectRnr');
                $selectRnd = $request->request->get('selectRnd');
                $txtTextMessage = $request->request->get('txtTextMessage');
                // $piecesJointeMessage = null;
                $piecesJointeMessage = $request->files->get('piecesJointeMessage');

                $newFilename = "";
                if ($piecesJointeMessage instanceof UploadedFile) {
                    $extensionOriginalFilename = pathinfo($piecesJointeMessage->getClientOriginalName(), PATHINFO_EXTENSION);
                    // Déplacez le fichier téléversé vers le répertoire de destination
                    // (vous devrez configurer le répertoire dans votre configuration VichUploaderBundle).

                    $newFilename = md5(uniqid()) . '.' . $extensionOriginalFilename;
                    $mappingConfig = $this->getParameter('vich_uploader.mappings')['uploads_retroinfo'];
                    // Accédez au répertoire de destination depuis la configuration
                    $uploadDestination = $mappingConfig['upload_destination'];

                    $piecesJointeMessage->move(
                        $uploadDestination, // Le répertoire de destination configuré dans VichUploader
                        $newFilename
                    );
                }

                $AllCentralSupervisors = $this->_userService->findAllCentralSupervisors();
                $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
                $AllDistrictAgents = $this->_userService->findAllDistrictAgents();
                // Persister le message
                if ($isAllCentral) {
                    foreach ($AllCentralSupervisors as $centralSupervisor) {
                        if ($centralSupervisor['idUser'] != $user->getId()) {
                            $recipient = $entityManager->getRepository(User::class)->find($centralSupervisor['idUser']);
                            $message = new Message();
                            $message->setSender($user);
                            $message->setRecipient($recipient);
                            $message->setIsRead(0);
                            $message->setDate(new \DateTime());
                            $message->setTextMessage($txtTextMessage);
                            $entityManager->persist($message);
                            $entityManager->flush();

                            if ($newFilename !== "") {
                                $piecejoint = new PieceJointe();
                                $piecejoint->setMessage($message);
                                $piecejoint->setFileName($newFilename);
                                $entityManager->persist($piecejoint);
                                $entityManager->flush();
                            }
                        }
                    }
                    $this->addFlash('success', '<strong>Message envoyé</strong><br/>');
                }
                if ($isAllRNR) {
                    foreach ($AllRegionalSupervisors as $regionalSupervisor) {
                        if ($regionalSupervisor['idUser'] != $user->getId()) {
                            $recipient = $entityManager->getRepository(User::class)->find($regionalSupervisor['idUser']);
                            $message = new Message();
                            $message->setSender($user);
                            $message->setRecipient($recipient);
                            $message->setIsRead(0);
                            $message->setDate(new \DateTime());
                            $message->setTextMessage($txtTextMessage);
                            $entityManager->persist($message);
                            $entityManager->flush();

                            if ($newFilename !== "") {
                                $piecejoint = new PieceJointe();
                                $piecejoint->setMessage($message);
                                $piecejoint->setFileName($newFilename);
                                $entityManager->persist($piecejoint);
                                $entityManager->flush();
                            }
                        }
                    }
                    $this->addFlash('success', '<strong>Message envoyé</strong><br/>');
                }
                if ($isAllRND) {
                    foreach ($AllDistrictAgents as $districtAgent) {
                        if ($districtAgent['idUser'] != $user->getId()) {
                            $recipient = $entityManager->getRepository(User::class)->find($districtAgent['idUser']);
                            $message = new Message();
                            $message->setSender($user);
                            $message->setRecipient($recipient);
                            $message->setIsRead(0);
                            $message->setDate(new \DateTime());
                            $message->setTextMessage($txtTextMessage);
                            $entityManager->persist($message);
                            $entityManager->flush();

                            if ($newFilename !== "") {
                                $piecejoint = new PieceJointe();
                                $piecejoint->setMessage($message);
                                $piecejoint->setFileName($newFilename);
                                $entityManager->persist($piecejoint);
                                $entityManager->flush();
                            }
                        }
                    }
                    $this->addFlash('success', '<strong>Message envoyé</strong><br/>');
                }
                if (!empty($selectCentral)) {
                    $recipient = $entityManager->getRepository(User::class)->find((int)$selectCentral);
                    $message = new Message();
                    $message->setSender($user);
                    $message->setRecipient($recipient);
                    $message->setIsRead(0);
                    $message->setDate(new \DateTime());
                    $message->setTextMessage($txtTextMessage);
                    $entityManager->persist($message);
                    $entityManager->flush();

                    if ($newFilename !== "") {
                        $piecejoint = new PieceJointe();
                        $piecejoint->setMessage($message);
                        $piecejoint->setFileName($newFilename);
                        $entityManager->persist($piecejoint);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', '<strong>Message envoyé</strong><br/>');
                }
                if (!empty($selectRnd)) {
                    $recipient = $entityManager->getRepository(User::class)->find((int)$selectRnd);
                    $message = new Message();
                    $message->setSender($user);
                    $message->setRecipient($recipient);
                    $message->setIsRead(0);
                    $message->setDate(new \DateTime());
                    $message->setTextMessage($txtTextMessage);
                    $entityManager->persist($message);
                    $entityManager->flush();

                    if ($newFilename !== "") {
                        $piecejoint = new PieceJointe();
                        $piecejoint->setMessage($message);
                        $piecejoint->setFileName($newFilename);
                        $entityManager->persist($piecejoint);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', '<strong>Message envoyé</strong><br/>');
                }
                if (!empty($selectRnr)) {
                    $recipient = $entityManager->getRepository(User::class)->find((int)$selectRnr);
                    $message = new Message();
                    $message->setSender($user);
                    $message->setRecipient($recipient);
                    $message->setIsRead(0);
                    $message->setDate(new \DateTime());
                    $message->setTextMessage($txtTextMessage);
                    $entityManager->persist($message);
                    $entityManager->flush();
                    if ($newFilename !== "") {
                        $piecejoint = new PieceJointe();
                        $piecejoint->setMessage($message);
                        $piecejoint->setFileName($newFilename);
                        $entityManager->persist($piecejoint);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', '<strong>Message envoyé</strong><br/>');
                }


                // var_dump(" isAllRNR " . $isAllRNR, 
                // "isAllRND " . $isAllRND, "selectRnr " . $selectRnr, "selectRnd " . $selectRnd, "txtTextMessage " . $txtTextMessage
                // , "piecesJointeMessage " . $piecesJointeMessage);
                // die();

                $sentMessages = $this->_messageService->findAllMessagebySender($user);
                $receivedMessages = $this->_messageService->findAllMessagebyRecipient($user);
                $unreadMessageCount = $entityManager->getRepository(Message::class)->count([
                    'recipient' => $user,
                    'isRead' => false,
                    'isDeletedPerRecipient' => null,
                ]);

                // CRENAS
                if ($this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {
                    $allStockDatas = $this->_stockDataService->findAllStockData($dataUser["idRegion"]);
                    $allStockDatasByStatus = $this->_stockDataService->findStockDataCountByStatus($dataUser["idRegion"]);
                    $allQuantitiesExpiry = $this->_quantitiesProductRiskExpiryService->findQuantitiesExpiryByDistrictAndRegion($dataUser["idRegion"]);
                    $allQuantitiesProductRiskExpiries = $this->_quantitiesProductRiskExpiryService->findAllQuantitiesProductRiskExpiries($dataUser["idRegion"]);
                    $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
                    $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
                    $AllDistrictAgents = $this->_userService->findAllDistrictAgents();
                } else {
                    $allStockDatas = $this->_stockDataService->findAllStockData();
                    $allStockDatasByStatus = $this->_stockDataService->findStockDataCountByStatus();
                    $allQuantitiesExpiry = $this->_quantitiesProductRiskExpiryService->findQuantitiesExpiryByDistrictAndRegion();
                    $allQuantitiesProductRiskExpiries = $this->_quantitiesProductRiskExpiryService->findAllQuantitiesProductRiskExpiries();
                    $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
                    $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
                    $AllDistrictAgents = $this->_userService->findAllDistrictAgents();
                }


                $nombreNormal = 0;
                $nombreSousStock = 0;
                $nombreRupture = 0;
                $nombreSurStock = 0;
                foreach ($allStockDatas as $key => $allStockData) {
                    $nombreNormal += $allStockData["nombreNormal"];
                    $nombreSousStock += $allStockData["nombreSoustock"];
                    $nombreRupture += $allStockData["nombreRupture"];
                    $nombreSurStock += $allStockData["nombreSurStock"];
                }
                $nombreallStockDatas = $nombreNormal + $nombreSousStock + $nombreRupture + $nombreSurStock;
                if ($nombreallStockDatas > 0) {
                    $pourcentageNormal = number_format(($nombreNormal / $nombreallStockDatas) * 100, 2);
                    $pourcentageSousStock = number_format(($nombreSousStock / $nombreallStockDatas) * 100, 2);
                    $pourcentageRupture = number_format(($nombreRupture / $nombreallStockDatas) * 100, 2);
                    $pourcentageSurStock = number_format(($nombreSurStock / $nombreallStockDatas) * 100, 2);
                } else {
                    $pourcentageNormal = 0;
                    $pourcentageSousStock = 0;
                    $pourcentageRupture = 0;
                    $pourcentageSurStock = 0;
                }

                // QUANTITE A EXPIRER D'ICI 3 MOIS 
                $quantitiesProductRiskExpiryPN = 0;
                $quantitiesProductRiskExpiryF75 = 0;
                $quantitiesProductRiskExpiryF100 = 0;
                foreach ($allQuantitiesProductRiskExpiries as $key => $quantitiesProductRiskExpiry) {
                    $quantitiesProductRiskExpiryPN += !empty($quantitiesProductRiskExpiry["PN"]) ? $quantitiesProductRiskExpiry["PN"] : 0;
                    $quantitiesProductRiskExpiryF75 += !empty($quantitiesProductRiskExpiry["F75"]) ? $quantitiesProductRiskExpiry["F75"] : 0;
                    $quantitiesProductRiskExpiryF100 += !empty($quantitiesProductRiskExpiry["F100"]) ? $quantitiesProductRiskExpiry["F100"] : 0;
                }
                if ($role === 'ROLE_AGENT_DISTRICT') {
                    return $this->render('message/homeMessage.html.twig', [
                        'controller_name' => 'MessageController',
                        'allCentralSupervisor' => $AllCentralSupervisors,
                        'allRegionalSupervisors' => $AllRegionalSupervisors,
                        'allDistrictAgents' => $AllDistrictAgents,
                        'sentMessages' => $sentMessages,
                        'receivedMessages' => $receivedMessages,
                        'unreadMessageCount' => $unreadMessageCount,
                        'isMessageSent' => true
                    ]);
                } elseif ($role == 'ROLE_CENTRAL_SUPERVISOR') {
                    return $this->render('supervisor/supervisorCentralHome1.html.twig', [
                        'controller_name' => 'MessageController',
                        'mnuActive' => 'Home1',
                        'pourcentagePNNormal' => $pourcentageNormal,
                        'pourcentagePNSousStock' => $pourcentageSousStock,
                        'pourcentagePNRupture' => $pourcentageRupture,
                        'pourcentagePNSurStock' => $pourcentageSurStock,
                        'quantitiesProductRiskExpiryPN' => $quantitiesProductRiskExpiryPN,
                        'quantitiesProductRiskExpiryF75' => $quantitiesProductRiskExpiryF75,
                        'quantitiesProductRiskExpiryF100' => $quantitiesProductRiskExpiryF100,
                        'allStockDatasByStatus' => $allStockDatasByStatus,
                        'allQuantitiesExpiry' => $allQuantitiesExpiry,
                        'allCentralSupervisor' => $AllCentralSupervisor,
                        'allRegionalSupervisors' => $AllRegionalSupervisors,
                        'allDistrictAgents' => $AllDistrictAgents,
                        'sentMessages' => $sentMessages,
                        'receivedMessages' => $receivedMessages,
                        'unreadMessageCount' => $unreadMessageCount,
                        'isMessageSent' => true
                    ]);
                }

                return $this->redirectToRoute('app_dashboard1', ['message_sent' => true]);
            } else {
                return $this->render('home.html.twig', [
                    'controller_name' => 'AccueilController',
                ]);
            }
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }


    #[Route('/deletemessage/{id}/{option}/{role}', name: 'app_dashboard1_retroinfo_delete_message', methods: ['GET', 'POST'])]
    public function deleteMessage(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        if ($user) {
            $role = $request->get('role');
            $idMessage = (int)$request->get('id');
            $option = $request->get('option');
            $message = $entityManager->getRepository(Message::class)->find($idMessage);
            if (!empty($message)) {
                if ($option === 'for_me') {
                    // Supprimer le message pour soi…
                    if ($user === $message->getSender()) {
                        $message->setIsDeletedPerSender(true);
                    } elseif ($user === $message->getRecipient()) {
                        $message->setIsDeletedPerRecipient(true);
                    }
                    $entityManager->flush();

                    $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
                    $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
                    $AllDistrictAgents = $this->_userService->findAllDistrictAgents();

                    $sentMessages = $this->_messageService->findAllMessagebySender($user);
                    $receivedMessages = $this->_messageService->findAllMessagebyRecipient($user);
                    $unreadMessageCount = $entityManager->getRepository(Message::class)->count([
                        'recipient' => $user,
                        'isRead' => false,
                        'isDeletedPerRecipient' => null,
                    ]);

                    if ($role == 'ROLE_AGENT_DISTRICT') {

                        return $this->render('message/homeMessage.html.twig', [
                            'controller_name' => 'MessageController',
                            'allCentralSupervisor' => $AllCentralSupervisor,
                            'allRegionalSupervisors' => $AllRegionalSupervisors,
                            'allDistrictAgents' => $AllDistrictAgents,
                            'sentMessages' => $sentMessages,
                            'receivedMessages' => $receivedMessages,
                            'unreadMessageCount' => $unreadMessageCount,
                            'isMessageDeleted' => true
                        ]);
                    }

                    return $this->redirectToRoute('app_dashboard1', ['deleted_m' => true]);
                } elseif ($option === 'for_all') {
                    // Supprimer le message pour tout le monde
                    $pieceJointeRepository = $entityManager->getRepository(PieceJointe::class);
                    $pieceJointes = $pieceJointeRepository->findBy(['message' => $message]);

                    $mappingConfig = $this->getParameter('vich_uploader.mappings')['uploads_retroinfo'];
                    // Accédez au répertoire de destination depuis la configuration
                    $uploadDestination = $mappingConfig['upload_destination'];

                    foreach ($pieceJointes as $pieceJointe) {
                        $filePath = $uploadDestination . '/' . $pieceJointe->getFileName();
                        if (file_exists($filePath)) {
                            // Supprimez le fichier existant.
                            unlink($filePath);
                        }
                        $entityManager->remove($pieceJointe);
                        $entityManager->flush();
                    }
                    $entityManager->remove($message);
                    $entityManager->flush();

                    $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
                    $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
                    $AllDistrictAgents = $this->_userService->findAllDistrictAgents();

                    $sentMessages = $this->_messageService->findAllMessagebySender($user);
                    $receivedMessages = $this->_messageService->findAllMessagebyRecipient($user);
                    $unreadMessageCount = $entityManager->getRepository(Message::class)->count([
                        'recipient' => $user,
                        'isRead' => false,
                        'isDeletedPerRecipient' => null,
                    ]);

                    if ($role === 'ROLE_AGENT_DISTRICT') {
                        return $this->redirectToRoute('app_retroinformation', ['deletebydistrict' => true]);
                    }

                    return $this->redirectToRoute('app_dashboard1', ['deleted_a' => true]);
                }
            }
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }

    #[Route('/updatemessage/{id}/{isread}/{role}', name: 'app_dashboard1_retroinfo_update_message', methods: ['GET', 'POST'])]
    public function updateMessage(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        if ($user) {            
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $role = $request->get('role');
            $idMessage = (int)$request->get('id');
            $isread = $request->get('isread');
            $message = $entityManager->getRepository(Message::class)->find($idMessage);
            if (!empty($message)) {
                if ($isread) {
                    $message->setIsRead(true);
                }
                $entityManager->flush();
            }
            $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
            $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
            $AllDistrictAgents = $this->_userService->findAllDistrictAgents();

            $sentMessages = $this->_messageService->findAllMessagebySender($user);
            $receivedMessages = $this->_messageService->findAllMessagebyRecipient($user);
           
            $unreadMessageCount = $entityManager->getRepository(Message::class)->count([
                'recipient' => $user,
                'isRead' => false,
                'isDeletedPerRecipient' => null,
            ]);

            if ($this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {
                $allStockDatas = $this->_stockDataService->findAllStockData($dataUser["idRegion"]);
                $allStockDatasByStatus = $this->_stockDataService->findStockDataCountByStatus($dataUser["idRegion"]);
                $allQuantitiesExpiry = $this->_quantitiesProductRiskExpiryService->findQuantitiesExpiryByDistrictAndRegion($dataUser["idRegion"]);
                $allQuantitiesProductRiskExpiries = $this->_quantitiesProductRiskExpiryService->findAllQuantitiesProductRiskExpiries($dataUser["idRegion"]);
                $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
                $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
                $AllDistrictAgents = $this->_userService->findAllDistrictAgents();
            } else {
                $allStockDatas = $this->_stockDataService->findAllStockData();
                $allStockDatasByStatus = $this->_stockDataService->findStockDataCountByStatus();
                $allQuantitiesExpiry = $this->_quantitiesProductRiskExpiryService->findQuantitiesExpiryByDistrictAndRegion();
                $allQuantitiesProductRiskExpiries = $this->_quantitiesProductRiskExpiryService->findAllQuantitiesProductRiskExpiries();
                $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
                $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
                $AllDistrictAgents = $this->_userService->findAllDistrictAgents();
            }

            $nombreNormal = 0;
            $nombreSousStock = 0;
            $nombreRupture = 0;
            $nombreSurStock = 0;
            foreach ($allStockDatas as $key => $allStockData) {
                $nombreNormal += $allStockData["nombreNormal"];
                $nombreSousStock += $allStockData["nombreSoustock"];
                $nombreRupture += $allStockData["nombreRupture"];
                $nombreSurStock += $allStockData["nombreSurStock"];
            }
            $nombreallStockDatas = $nombreNormal + $nombreSousStock + $nombreRupture + $nombreSurStock;
            if ($nombreallStockDatas > 0) {
                $pourcentageNormal = number_format(($nombreNormal / $nombreallStockDatas) * 100, 2);
                $pourcentageSousStock = number_format(($nombreSousStock / $nombreallStockDatas) * 100, 2);
                $pourcentageRupture = number_format(($nombreRupture / $nombreallStockDatas) * 100, 2);
                $pourcentageSurStock = number_format(($nombreSurStock / $nombreallStockDatas) * 100, 2);
            } else {
                $pourcentageNormal = 0;
                $pourcentageSousStock = 0;
                $pourcentageRupture = 0;
                $pourcentageSurStock = 0;
            }

            // QUANTITE A EXPIRER D'ICI 3 MOIS 
            $quantitiesProductRiskExpiryPN = 0;
            $quantitiesProductRiskExpiryF75 = 0;
            $quantitiesProductRiskExpiryF100 = 0;
            foreach ($allQuantitiesProductRiskExpiries as $key => $quantitiesProductRiskExpiry) {
                $quantitiesProductRiskExpiryPN += !empty($quantitiesProductRiskExpiry["PN"]) ? $quantitiesProductRiskExpiry["PN"] : 0;
                $quantitiesProductRiskExpiryF75 += !empty($quantitiesProductRiskExpiry["F75"]) ? $quantitiesProductRiskExpiry["F75"] : 0;
                $quantitiesProductRiskExpiryF100 += !empty($quantitiesProductRiskExpiry["F100"]) ? $quantitiesProductRiskExpiry["F100"] : 0;
            }

            if ($role === 'ROLE_AGENT_DISTRICT') {
                return $this->render('message/homeMessage.html.twig', [
                    'controller_name' => 'MessageController',
                    'allCentralSupervisor' => $AllCentralSupervisor,
                    'allRegionalSupervisors' => $AllRegionalSupervisors,
                    'allDistrictAgents' => $AllDistrictAgents,
                    'sentMessages' => $sentMessages,
                    'receivedMessages' => $receivedMessages,
                    'unreadMessageCount' => $unreadMessageCount
                ]);
            } elseif ($role === 'ROLE_CENTRAL_SUPERVISOR' ||$role === 'ROLE_REGIONAL_SUPERVISOR') {
                return $this->render('supervisor/supervisorCentralHome1.html.twig', [
                    'controller_name' => 'MessageController',
                    'mnuActive' => 'Home1',
                    'pourcentagePNNormal' => $pourcentageNormal,
                    'pourcentagePNSousStock' => $pourcentageSousStock,
                    'pourcentagePNRupture' => $pourcentageRupture,
                    'pourcentagePNSurStock' => $pourcentageSurStock,
                    'quantitiesProductRiskExpiryPN' => $quantitiesProductRiskExpiryPN,
                    'quantitiesProductRiskExpiryF75' => $quantitiesProductRiskExpiryF75,
                    'quantitiesProductRiskExpiryF100' => $quantitiesProductRiskExpiryF100,
                    'allStockDatasByStatus' => $allStockDatasByStatus,
                    'allQuantitiesExpiry' => $allQuantitiesExpiry,
                    'allCentralSupervisor' => $AllCentralSupervisor,
                    'allRegionalSupervisors' => $AllRegionalSupervisors,
                    'allDistrictAgents' => $AllDistrictAgents,
                    'sentMessages' => $sentMessages,
                    'receivedMessages' => $receivedMessages,
                    'unreadMessageCount' => $unreadMessageCount
                ]);
            }
            return $this->redirectToRoute('app_accueil', ['uptadedread' => true]);
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }

    #[Route('/dashboard/synchronise', name: 'app_sp_accueil_sychronise_dashboard')]
    public function synchroniseDashboard(EntityManagerInterface $entityManager, ManagerRegistry $registry): Response
    {
        $user = $this->getUser();
        if ($user) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/RMANut/';
            $rmanutService = new RmanutService($entityManager, $registry);

            // CRENAS
            $allStockDatas = $rmanutService->extractExcelForStockData($entityManager, $filePath);
            $nombreallStockDatas = $allStockDatas['ruptureCount'] + $allStockDatas['sousStockCount'] + $allStockDatas['normalCount'] + $allStockDatas['surStockCount'];
            $pourcentageNormal = number_format(($allStockDatas['normalCount'] / $nombreallStockDatas) * 100, 2);
            $pourcentageSousStock = number_format(($allStockDatas['sousStockCount'] / $nombreallStockDatas) * 100, 2);
            $pourcentageRupture = number_format(($allStockDatas['ruptureCount'] / $nombreallStockDatas) * 100, 2);
            $pourcentageSurStock = number_format(($allStockDatas['surStockCount'] / $nombreallStockDatas) * 100, 2);

            $rmanutService = new RmanutService($entityManager, $registry);
            $allQuantitiesProductRiskExpiry = $rmanutService->extractExcelForQuantitiesProductRiskExpiry($entityManager, $filePath);

            $quantitiesProductRiskExpiryPN = $allQuantitiesProductRiskExpiry['PN'];
            $quantitiesProductRiskExpiryF75 = $allQuantitiesProductRiskExpiry['F75'];
            $quantitiesProductRiskExpiryF100 = $allQuantitiesProductRiskExpiry['F100'];
            // $quantitiesProductRiskExpiryAMOXI = 0;

            // echo "**********nombreNormal************";
            // var_dump(number_format(($nombreNormal / $nombreallStockDatas) * 100, 2));
            // echo "*********nombreSousStock*************";
            // var_dump(number_format(($nombreSousStock / $nombreallStockDatas) * 100, 2));
            // echo "***********nombreRupture***********";
            // var_dump(number_format(($nombreRupture / $nombreallStockDatas) * 100, 2));
            // echo "************nombreSurStock**********";
            // var_dump(number_format(($nombreSurStock / $nombreallStockDatas) * 100, 2));
            // // echo "**********************";
            // var_dump($allStockDatas);
            // die();


            $allStockDatasByStatus = $this->_stockDataService->findStockDataCountByStatus();
            $allQuantitiesExpiry = $this->_quantitiesProductRiskExpiryService->findQuantitiesExpiryByDistrictAndRegion();
            return $this->redirectToRoute('app_dashboard1');
            // return $this->render('supervisor/supervisorCentralHome1.html.twig', [
            //     'controller_name' => 'AccueilController',
            //     'mnuActive' => 'Home1',
            //     'pourcentagePNNormal' => $pourcentageNormal,
            //     'pourcentagePNSousStock' => $pourcentageSousStock,
            //     'pourcentagePNRupture' => $pourcentageRupture,
            //     'pourcentagePNSurStock' => $pourcentageSurStock,
            //     'quantitiesProductRiskExpiryPN' => $quantitiesProductRiskExpiryPN,
            //     'quantitiesProductRiskExpiryF75' => $quantitiesProductRiskExpiryF75,
            //     'quantitiesProductRiskExpiryF100' => $quantitiesProductRiskExpiryF100,
            //     'allStockDatasByStatus' => $allStockDatasByStatus,
            //     'allQuantitiesExpiry' => $allQuantitiesExpiry
            // ]);
        }
    }

    #[Route('/supervisor/rmaNut', name: 'app_accueil_sp_rmanut')]
    public function showRMANutForSupervisor(CommandeTrimestrielleRepository $commandeTrimestrielleRepository)
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $currentCommande = $commandeTrimestrielleRepository->findOneBy(['isActive' => true]);
            
            if ($this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {
                $lstRegionRMANut = $this->_rmaNutService->findAllRegionRmaNut($dataUser["idRegion"], $currentCommande);
            } else {
                $lstRegionRMANut = $this->_rmaNutService->findAllRegionRmaNut(null, $currentCommande);
            }
            return $this->render('supervisor/supervisorCentralRegionRmaNut.html.twig', [
                "mnuActive" => "RMANut",
                "dataUser" => $dataUser,
                "lstRegionRMANut" => $lstRegionRMANut
            ]);
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }

    #[Route('/supervisor/pvrd', name: 'app_accueil_sp_pvrd')]
    public function showPvrdForSupervisor(CommandeTrimestrielleRepository $commandeTrimestrielleRepository)
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $currentCommande = $commandeTrimestrielleRepository->findOneBy(['isActive' => true]);
            if ($this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {
                $lstRegionPvrd = $this->_pvrdService->findAllRegionPvrd($dataUser["idRegion"], $currentCommande);
            } else {
                $lstRegionPvrd = $this->_pvrdService->findAllRegionPvrd(null, $currentCommande);
            }
            return $this->render('supervisor/supervisorCentralRegionPvrd.html.twig', [
                "mnuActive" => "Pvrd",
                "dataUser" => $dataUser,
                "lstRegionPvrd" => $lstRegionPvrd
            ]);
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }

    #[Route('/supervisor/groupe', name: 'app_accueil_sp_groupe')]
    public function showGroupeCrenasForSupervisor()
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataAnneePrevisionnelle = $this->_anneePrevisionnelleService->findDataAnneePrevisionnelle();
            //$lstGroupe = $this->_groupeService->findAllDataGroupe($dataAnneePrevisionnelle["IdAnneePrevisionnelle"]);
            //$lstGroupe = $this->_groupeService->findAllDataGroupeRegionale($dataAnneePrevisionnelle["IdAnneePrevisionnelle"], $dataUser['idRegion']);
            $lstGroupe = $this->_groupeService->findAllDataGroupeRegionale($dataAnneePrevisionnelle["IdAnneePrevisionnelle"]);
           
            $lstGroupData = [];
            if (isset($lstGroupe) && is_array($lstGroupe) && count($lstGroupe) > 0) {
                for ($i = 0; $i < count($lstGroupe); $i++) {
                    $lstGroupData[$lstGroupe[$i]["nomGroupe"]][] = $lstGroupe[$i];
                }
            }
            return $this->render('supervisor/supervisorCentralGroupeCrenas.html.twig', [
                "mnuActive" => "GroupeCrenas",
                "dataUser" => $dataUser,
                "lstGroupData" => $lstGroupData
            ]);
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }

    #[Route('/supervisor/crenas/group/{groupId}', name: 'app_accueil_sp_crenas_in_groupe')]
    public function showCrenasInGroupForSupervisor($groupId, EntityManagerInterface $entityManagery)
    {
        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataGroupe = $this->_groupeService->findById($groupId);

            // Obtenir les informations concernant les données crenas enregistrer
            $lstDataCrenasGroupe = array();

            if ($this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {
                $arrDataCrenasGroupe =  $this->_dataCrenaService->findDataCrenasByGroupeByRegion($groupId, $dataUser['idRegion']);

                if ($arrDataCrenasGroupe != null && is_array($arrDataCrenasGroupe) && count($arrDataCrenasGroupe) > 0) {
                    foreach ($arrDataCrenasGroupe as $dataCrenasGroupe) {
                        if (isset($dataCrenasGroupe) && is_array($dataCrenasGroupe) && count($dataCrenasGroupe) > 0) {
                            for ($i = 0; $i < count($dataCrenasGroupe); $i++) {
                                if (array_key_exists($i, $dataCrenasGroupe)) {
                                    $lstDataCrenasGroupe[] = $dataCrenasGroupe[$i];
                                }
                            }
                        }
                    }
                }
            }

            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CENTRAL_SUPERVISOR')) {
                $arrDataCrenasGroupe =  $this->_dataCrenaService->findAllDataCrenasByRegionId($groupId);

                if ($arrDataCrenasGroupe != null && is_array($arrDataCrenasGroupe) && count($arrDataCrenasGroupe) > 0) {
                    foreach ($arrDataCrenasGroupe as $dataCrenasGroupe) {
                        if (isset($dataCrenasGroupe) && is_array($dataCrenasGroupe) && count($dataCrenasGroupe) > 0) {
                            //for ($i = 0; $i < count($dataCrenasGroupe); $i++) {
                                    $lstDataCrenasGroupe[] = $dataCrenasGroupe;
                                
                            //}
                        }
                    }
                }
            }
            //$arrDataCrenasGroupe =  $this->_dataCrenaService->findDataCrenasByGroupe($groupId);
            
           // dd($arrDataCrenasGroupe);
            //dd($groupId, $dataUser, $arrDataCrenasGroupe);
           

            $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
            $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($groupId, $dataCommandeTrimestrielle['idCommandeTrimestrielle']);

            $lstMoisAdmissionCRENASAnneePrecedent = array();
            $lstMoisAdmissionProjeteAnneePrecedent = array();
            $lstMoisProjectionAnneePrevisionnelle = array();

            if (isset($dataMoisProjection) && count($dataMoisProjection) > 0) {
                for ($i = 0; $i < count($dataMoisProjection); $i++) {
                    $lstMoisAdmissionCRENASAnneePrecedent[] = $dataMoisProjection[$i]["MoisAdmissionCRENASAnneePrecedent"];
                    $lstMoisAdmissionProjeteAnneePrecedent[] = $dataMoisProjection[$i]["MoisAdmissionProjeteAnneePrecedent"];
                    $lstMoisProjectionAnneePrevisionnelle[] = $dataMoisProjection[$i]["MoisProjectionAnneePrevisionnelle"];
                }
            }

             // Gestion enclave
             $isEnclave = false;
             
             if ($dataGroupe[0]['type'] != null && $dataGroupe[0]['type'] != "" && $dataGroupe[0]['type'] == "enclave") {
                 $isEnclave = true;
                 $allMoisPrevisionnelle = $this->_moisProjectionAdmissionService->findDataMoisPrevisionnelleProjection($dataGroupe[0]['idGroupe']);
                 if (count($allMoisPrevisionnelle) > 0) {
                     foreach($allMoisPrevisionnelle as $moisPrevision) {
                         if (!in_array($moisPrevision['MoisProjectionAnneePrevisionnelle'], $lstMoisProjectionAnneePrevisionnelle)) {
                             array_push($lstMoisProjectionAnneePrevisionnelle, $moisPrevision['MoisProjectionAnneePrevisionnelle']);
                         }
                     }
                 }
                // dd($dataGroupe, $lstMoisProjectionAnneePrevisionnelle, $allMoisPrevisionnelle);
             }
           
             // Gestion enclave
            $valueDataMoisProjection = null;
            $lstValueMoisAdmissionCRENASAnneePrecedent = array();
            $lstValueMoisAdmissionProjeteAnneePrecedent = array();
            $lstValueMoisProjectionAnneePrevisionnelle = array();

            if (isset($lstDataCrenasGroupe) && is_array($lstDataCrenasGroupe) && count($lstDataCrenasGroupe) > 0) {
                for ($j = 0; $j < count($lstDataCrenasGroupe); $j++) {
                    $dataCrenasId = $lstDataCrenasGroupe[$j]["id"];
                    $valueDataMoisProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasId($dataCrenasId);
                    if ($isEnclave) {
                        $valueDataMoisPrevisionneleProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisPrevisionnelleAdmissionByCrenasId($dataCrenasId);
                        $valueDataMoisProjection = array_merge($valueDataMoisProjection, $valueDataMoisPrevisionneleProjection);
                        //dd($valueDataMoisPrevisionneleProjection, $valueDataMoisProjection);
                    }
                    

                    if (isset($valueDataMoisProjection) && count($valueDataMoisProjection) > 0) {
                        for ($i = 0; $i < count($valueDataMoisProjection); $i++) {
                            $lstValueMoisAdmissionCRENASAnneePrecedent[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisAdmissionCRENASAnneePrecedent"];
                            $lstValueMoisAdmissionProjeteAnneePrecedent[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisAdmissionProjeteAnneePrecedent"];
                            $lstValueMoisProjectionAnneePrevisionnelle[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisProjectionAnneePrevisionnelle"];
                        }
                    }
                }
            }
           
            return $this->render('supervisor/supervisorCentralCrenas.html.twig', [
                "mnuActive" => "GroupeCrenas",
                "dataUser" => $dataUser,
                "lstDataCrenasGroupe" => $lstDataCrenasGroupe,
                "dataMoisProjection" => $dataMoisProjection,
                "dataGroupe" => $dataGroupe,
                "lstMoisAdmissionCRENASAnneePrecedent" => $lstMoisAdmissionCRENASAnneePrecedent,
                "lstMoisAdmissionProjeteAnneePrecedent" => $lstMoisAdmissionProjeteAnneePrecedent,
                "lstMoisProjectionAnneePrevisionnelle" => $lstMoisProjectionAnneePrevisionnelle,
                "lstValueMoisAdmissionCRENASAnneePrecedent" => $lstValueMoisAdmissionCRENASAnneePrecedent,
                "lstValueMoisAdmissionProjeteAnneePrecedent" => $lstValueMoisAdmissionProjeteAnneePrecedent,
                "lstValueMoisProjectionAnneePrevisionnelle" => $lstValueMoisProjectionAnneePrevisionnelle,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/supervisor/region/creni', name: 'app_accueil_sp_region_creni')]
    public function showRegionCreniForSupervisor()
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            if ($this->isGranted('ROLE_REGIONAL_SUPERVISOR')) {
                $lstRegionCreni = $this->_dataCreniService->findAllRegionCreni($dataUser["idRegion"]);
            } else {
                $lstRegionCreni = $this->_dataCreniService->findAllRegionCreni();
            }

            return $this->render('supervisor/supervisorCentralRegionCreni.html.twig', [
                "mnuActive" => "RegionCreni",
                "dataUser" => $dataUser,
                "lstRegionCreni" => $lstRegionCreni
            ]);
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }

    #[Route('/supervisor/creni/region/{regionId}', name: 'app_accueil_sp_creni_from_region')]
    public function showCreniFromRegion($regionId, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);

            $Region = $entityManager->getRepository(Region::class)->find($regionId);
            if (!$Region) {
                throw $this->createNotFoundException('La région n\'existe pas.');
            }
            $lstCreniRegion = $this->_dataCreniService->findListCreniByRegion($regionId);

            // Obtenez le nombre total de districts dans la région
            $numberOfDistricts = $Region->getDistricts()->count();
            $districtsDataCreni = array();
            $dataCommandeSemestrielle = $this->_commandeSemestrielleService->findDataCommandeSemestrielle();
            $dataMoisProjection = $this->_creniMoisProjectionAdmissionService->findDataMoisProjection($dataCommandeSemestrielle["idCommandeSemestrielle"]);

            // Obtenir les informations concernant les données creni enregistrer 
            $lstDataCreniRegion = array();
            $arrDataCreniRegion = $this->_dataCreniService->findDataCreniByRegionId($regionId);
            if ($arrDataCreniRegion != null && is_array($arrDataCreniRegion) && count($arrDataCreniRegion) > 0) {
                foreach ($arrDataCreniRegion as $dataCreniRegion) {
                    $lstDataCreniRegion[] = $dataCreniRegion;
                }
            }

            $dataCreniMoisProjetionAdmission = null;
            if (isset($lstDataCreniRegion) && is_array($lstDataCreniRegion) && count($lstDataCreniRegion) > 0) {
                for ($i = 0; $i < count($lstDataCreniRegion); $i++) {
                    $dataCreniMoisProjetionAdmission[$lstDataCreniRegion[$i]["id"]] = $this->_dataCreniMoisProjectionAdmissionService->findDataCreniMoisProjectionAdmissionByCreniIdAndMoisProjection($lstDataCreniRegion[$i]["id"], $dataCommandeSemestrielle["idCommandeSemestrielle"]);
                }
            }

            return $this->render('supervisor/supervisorCentralCreniRegion.html.twig', [
                "mnuActive" => "RegionCreni",
                "dataUser" => $dataUser,
                "dataRegion" => $Region,
                "dataMoisProjection" => $dataMoisProjection,
                "dataCreniMoisProjetionAdmission" => $dataCreniMoisProjetionAdmission,
                "lstCreniRegion" => $lstCreniRegion,
                "lstDataCreniRegion" => $lstDataCreniRegion,
                "numberOfDistricts" => $numberOfDistricts,
                "districtsDataCreni" => $districtsDataCreni
            ]);
        } else {
            return $this->render('home.html.twig', [
                'controller_name' => 'AccueilController',
            ]);
        }
    }

    // Afficher la page de gestion des mouvements de produits
    #[Route('/supervisor/district/movement/products', name: 'app_accueil_sp_mouvement_products')]
    public function showMovementProducts()
    {
        $allDistricts = $this->_rmaNutService->FindAllDistrict();
        return $this->render('supervisor/supevisormovementproducts.html.twig', [
            "mnuActive" => "MovementProducts",
            "allDistricts" => $allDistricts
        ]);
    }

    // Afficher le tableau concernant les mouvements de produits : Canvas SDSP
    #[Route('/supervisor/district/movement/products/ajaxCanvassdsp', name: 'app_show_movement_products_ajaxCanvassdsp', methods: ['GET'])]
    public function ajaxShowMovementProductsCanvasSDSP(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $selectedDistrict = $request->query->get('district');

            $data = [];
            if ($selectedDistrict != null) {
                $rmanut = $this->_rmaNutService->findListRMANutByDistrict($selectedDistrict);
                $excelService = new ExcelService();
                $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/RMANut/' . $rmanut[0]["newFileName"];
                if (file_exists($filePath)) {
                    $sheetName = 'CANEVAS SDSP';
                    
                    // CRENAS
                    $startRow = 27;
                    $startColumn = 1;
                    $endRow = 38;
                    $endColumn = 11;

                    // CRENI : F100
                    $startRowF100 = 58;
                    $startColumnF100 = 1;
                    $endRowF100 = 69;
                    $endColumnF100 = 10;

                    // CRENI : F75
                    $startRowF75 = 58;
                    $startColumnF75 = 12;
                    $endRowF75 = 69;
                    $endColumnF75 = 21;

                    // CRENI : PN
                    $startRowPN = 58;
                    $startColumnPN = 23;
                    $endRowPN = 69;
                    $endColumnPN = 32;

                    try {
                        //code...
                        $result1 = $excelService->extractData($filePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
                        $result2 = $excelService->extractData($filePath, $sheetName, $startRowF100, $startColumnF100, $endRowF100, $endColumnF100);
                        $result3 = $excelService->extractData($filePath, $sheetName, $startRowF75, $startColumnF75, $endRowF75, $endColumnF75);
                        $result4 = $excelService->extractData($filePath, $sheetName, $startRowPN, $startColumnPN, $endRowPN, $endColumnPN);
                        $data[0] = $result1;
                        $data[1][0] = $result2;
                        $data[1][1] = $result3;
                        $data[1][2] = $result4;
                    } catch (\Throwable $th) {
                    }

                } else {
                    echo "Le fichier n'existe pas.";
                }
            }
            // Votre logique pour récupérer les données du tableau ici


            return new JsonResponse($data);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Afficher le tableau concernant les mouvements de produits : Gestion PN CSB
    #[Route('/supervisor/district/movement/products/ajaxpncsb', name: 'app_show_movement_products_ajaxPnCsb', methods: ['GET'])]
    public function ajaxShowMovementProductsPNCSB(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $registry): JsonResponse
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $selectedDistrict = $request->query->get('district');

            $data = [];
            if ($selectedDistrict != null) {
                $rmanut = $this->_rmaNutService->findListRMANutByDistrict($selectedDistrict);
                $excelService = new ExcelService();
                $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/RMANut/' . $rmanut[0]["newFileName"];
                if (file_exists($filePath)) {
                    try {
                        //code...
                        // $result = $excelService->extractData($filePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
                        $rmanutService = new RMANutService($entityManager, $registry);
                        $resultCsb = $rmanutService->extractCsb($filePath);

                        $sheetName = 'GESTION PN CSB';
                        $startRowRecues = 11;
                        $startColumnRecues = 18;
                        $endRowRecues = $startRowRecues + count($resultCsb);
                        $endColumnRecues = 29;

                        $excelService = new ExcelService();
                        $resultRecues = $excelService->extractData($filePath, $sheetName, $startRowRecues, $startColumnRecues, $endRowRecues, $endColumnRecues);

                        $startRowSorties = 11;
                        $startColumnSorties = 54;
                        $endRowSorties = $startRowSorties + count($resultCsb);
                        $endColumnSorties = 65;

                        $excelService = new ExcelService();
                        $resultSorties = $excelService->extractData($filePath, $sheetName, $startRowSorties, $startColumnSorties, $endRowSorties, $endColumnSorties);
                        
                        $data[0] = $resultCsb;
                        $data[1] = $resultRecues;
                        $data[2] = $resultSorties;

                    } catch (\Throwable $th) {
                    }
                    
                } else {
                    echo "Le fichier n'existe pas.";
                }
            }
            // Votre logique pour récupérer les données du tableau ici


            return new JsonResponse($data);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    // Afficher le tableau concernant les mouvements de produits : Gestion PN SDU
    #[Route('/supervisor/district/movement/products/ajaxpnsdu', name: 'app_show_movement_products_ajax_pnsdu', methods: ['GET'])]
    public function ajaxShowMovementProductsPNSDU(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $registry): JsonResponse
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $selectedDistrict = $request->query->get('district');

            $data = [];
            if ($selectedDistrict != null) {
                $rmanut = $this->_rmaNutService->findListRMANutByDistrict($selectedDistrict);
                $excelService = new ExcelService();
                $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/RMANut/' . $rmanut[0]["newFileName"];
                if (file_exists($filePath)) {
                    try {
                        //code...
                        // $result = $excelService->extractData($filePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
                        $rmanutService = new RMANutService($entityManager, $registry);
                        $resultCsb = $rmanutService->extractCsb($filePath);

                        $sheetName = 'GESTION PN  SDU';
                        $startRow = 11;
                        $startColumn = 30;
                        $endRow = $startRow + count($resultCsb);
                        $endColumn = 41;

                        $excelService = new ExcelService();
                        $result = $excelService->extractData($filePath, $sheetName, $startRow, $startColumn, $endRow, $endColumn);
                        
                        $data[0] = $resultCsb;
                        $data[1] = $result;

                    } catch (\Throwable $th) {
                    }
                    
                } else {
                    echo "Le fichier n'existe pas.";
                }
            }
            // Votre logique pour récupérer les données du tableau ici


            return new JsonResponse($data);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    public function shiftColumn($column, $shift)
    {
        $column = strtoupper($column);
        $letters = preg_split('//u', $column, -1, PREG_SPLIT_NO_EMPTY);

        $result = '';

        foreach (array_reverse($letters) as $letter) {
            $ascii = ord($letter) + $shift;
            $shift = 0;

            if ($ascii > ord('Z')) {
                $ascii -= 26;
                $shift = 1;
            }

            $result = chr($ascii) . $result;
        }

        if ($shift) {
            $result = 'A' . $result;
        }

        return $result;
    }

    public function styleApply($sheet, $col, $row = 0, $isFirstRow = 0, $isCenterText = 1, $isLeftText = 0, $colspan = 0, $rowspan = 0)
    {
        $colspan = ($colspan == 0) ? $col : $colspan;
        $rowspan = ($rowspan == 0) ? $row : $rowspan;

        // Style pour la première ligne (en gras et bordures)
        $styleFirstRow = [
            'font' => ['bold' => true],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        // Style pour centrer le texte
        $styleCenterTextWithBorder = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $styleLeftTextWithBorder = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getColumnDimension($col)->setAutoSize(true);
        if ($isFirstRow) {
            if ($colspan != 0 && $rowspan != 0) {
                $sheet->getStyle($col . $row . ':' . $colspan . $rowspan)->applyFromArray($styleFirstRow);
            } else {
                $sheet->getStyle($col . $row)->applyFromArray($styleFirstRow);
            }
        }
        if ($isCenterText) {
            if ($colspan != 0 && $rowspan != 0) {
                $sheet->getStyle($col . $row . ':' . $colspan . $rowspan)->applyFromArray($styleCenterTextWithBorder);
            } else {
                $sheet->getStyle($col . $row)->applyFromArray($styleCenterTextWithBorder);
            }
        }
        if ($isLeftText) {
            if ($colspan != 0 && $rowspan != 0) {
                $sheet->getStyle($col . $row . ':' . $colspan . $rowspan)->applyFromArray($styleLeftTextWithBorder);
            } else {
                $sheet->getStyle($col . $row)->applyFromArray($styleLeftTextWithBorder);
            }
        }
    }
}
