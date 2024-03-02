<?php

namespace App\Controller;

use App\Entity\CommandeTrimestrielle;
use App\Entity\DataValidationCrenas;
use App\Entity\District;
use App\Entity\Region;
use App\Entity\RmaNut;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Finder\GroupeFinder;
use App\Finder\UserFinder;
use App\Finder\AnneePrevisionelleFinder;
use App\Finder\CommandeSemestrielleFinder;
use App\Finder\CommandeTrimestrielleFinder;
use App\Finder\CreniMoisProjectionAdmissionFinder;
use App\Finder\DataCrenasFinder;
use App\Finder\DataCrenasMoisProjectionAdmissionFinder;
use App\Finder\DataCreniFinder;
use App\Finder\DataCreniMoisProjectionAdmissionFinder;
use App\Finder\DataValidationCrenasFinder;
use App\Finder\DataValidationCreniFinder;
use App\Finder\MoisProjectionAdmissionFinder;
use App\Finder\RmaNutFinder;
use App\Finder\RegionFinder;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RmaNutController extends AbstractController
{
    private $_userService;
    private $_anneePrevisionnelleService;
    private $_groupeService;
    private $_commandeTrimestrielleService;
    private $_dataCrenaService;
    private $_dataCreniService;
    private $_rmaNutService;
    private $_regionService;
    private $_dataCrenasMoisProjectionAdmission;
    private $_moisProjectionAdmissionService;
    private $_commandeSemestrielleService;
    private $_creniMoisProjectionAdmissionService;
    private $_dataCreniMoisProjectionAdmissionService;
    private $_dataValidationCrenasService;
    private $_dataValidationCreniService;
    private $logger;

    public function __construct(LoggerInterface $logger, 
        UserFinder $user_service_container, 
        DataCrenasFinder $data_crenas_container, 
        DataCreniFinder $data_creni_container, 
        CommandeSemestrielleFinder $commande_semestrielle_container, 
        AnneePrevisionelleFinder $annee_previonnelle_container,  
        MoisProjectionAdmissionFinder $mois_projection_admission_container, 
        RmaNutFinder $rma_nut_container, 
        RegionFinder $region_container, 
        GroupeFinder $groupe_container, 
        CommandeTrimestrielleFinder $commande_trimetrielle_container, 
        CreniMoisProjectionAdmissionFinder $creni_mois_projection_admission_container, 
        DataCrenasMoisProjectionAdmissionFinder $data_crenas_mois_projection_admission, 
        DataCreniMoisProjectionAdmissionFinder $data_creni_mois_projection_admission_container,
        DataValidationCrenasFinder $data_validation_crenas_container,
        DataValidationCreniFinder $data_validation_creni_container
    )
    {
        $this->_userService = $user_service_container;
        $this->_anneePrevisionnelleService = $annee_previonnelle_container;
        $this->_groupeService = $groupe_container;
        $this->_commandeTrimestrielleService = $commande_trimetrielle_container;
        $this->_moisProjectionAdmissionService = $mois_projection_admission_container;
        $this->_dataCrenaService = $data_crenas_container;
        $this->_dataCreniService = $data_creni_container;
        $this->_rmaNutService = $rma_nut_container;
        $this->_regionService = $region_container;
        $this->_commandeSemestrielleService = $commande_semestrielle_container;
        $this->_creniMoisProjectionAdmissionService = $creni_mois_projection_admission_container;
        $this->_dataCrenasMoisProjectionAdmission = $data_crenas_mois_projection_admission;
        $this->_dataCreniMoisProjectionAdmissionService = $data_creni_mois_projection_admission_container;
        $this->_dataValidationCrenasService = $data_validation_crenas_container;
        $this->_dataValidationCreniService = $data_validation_creni_container;
        $this->logger = $logger;
    }

    #[Route('/rmanut', name: 'app_rmanut')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataAnneePrevisionnelle = $this->_anneePrevisionnelleService->findDataAnneePrevisionnelle();
            $dataGroupe = $this->_groupeService->findDataGroupe($dataAnneePrevisionnelle["IdAnneePrevisionnelle"], $dataUser["provinceId"], $dataUser['idDistrict']);
            $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
            $dataRMANut = $this->_rmaNutService->findDataRmaNutByUserCommandeTrimestrielle($userId, $dataCommandeTrimestrielle['idCommandeTrimestrielle']);

            return $this->render('rmanut/homeRmaNut.html.twig', [
                'controller_name' => 'RmaNutController',
                "dataUser" => $dataUser,
                "dataGroupe" => $dataGroupe,
                "dataRMANut" => $dataRMANut
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/rmanut/upload', name: 'app_rmanut_uploadFile')]
    public function uploadFileRMANut(Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
        $user = $this->getUser(); // Supposons que cela retourne l'utilisateur actuellement connecté.

        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);

            // Récupérez le fichier téléversé depuis la requête.
            $file = $request->files->get('rmanut_file'); // Assurez-vous que 'rmanut_file' correspond au nom de votre champ de téléversement dans le formulaire.
            if ($file instanceof UploadedFile) {
                // Vérifiez l'extension du fichier (assurez-vous d'ajuster les extensions autorisées selon vos besoins).
                $allowedExtensions = ['xlsx', 'xls'];
                $extensionOriginalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                if (!in_array($extensionOriginalFilename, $allowedExtensions)) {
                    // Redirigez l'utilisateur avec un message d'erreur en cas d'extension non autorisée.
                    $this->addFlash('error', '<strong>Erreur de fichier téléverser</strong><br/> Le format accepté est le format <b>Microsoft Excel</b> au format <b>.xls</b> ou <b>.xlsx</b> .');
                    return $this->redirectToRoute('app_rmanut');
                }

                $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
                // Générez un nom de fichier unique en utilisant le format souhaité.
                $NomUser = preg_replace('/[^a-zA-Z0-9_]/', '', $dataUser["nomUser"]);
                $NomDistrict = strtolower($dataUser["nomDistrict"]);
                $NomCHU = preg_replace('/[^a-zA-Z0-9_]/', '', $dataUser["nomCHU"]);
                if ($NomCHU == "") {
                    $newFilename = 'RMA_NUT_' . $dataCommandeTrimestrielle["Slug"] . '_' . date('Ymd') . '_'  . $NomUser . '_' . $NomDistrict . '.' . $extensionOriginalFilename;
                } else {
                    $newFilename = 'RMA_NUT_' . $dataCommandeTrimestrielle["Slug"] . '_' . date('Ymd') . '_'  . $NomUser . '_' . $NomDistrict . '_' . $NomCHU . '.' . $extensionOriginalFilename;
                }
                // Déplacez le fichier téléversé vers le répertoire de destination (vous devrez configurer le répertoire dans votre configuration VichUploaderBundle).

                $mappingConfig = $this->getParameter('vich_uploader.mappings')['uploads_rmanut'];
                // Accédez au répertoire de destination depuis la configuration
                $uploadDestination = $mappingConfig['upload_destination'];

                $file->move(
                    $uploadDestination, // Le répertoire de destination configuré dans VichUploader
                    $newFilename
                );

                $CommandeTrimestrielle = $entityManager->getRepository(CommandeTrimestrielle::class)->find($dataCommandeTrimestrielle["idCommandeTrimestrielle"]);
                $District = $entityManager->getRepository(District::class)->find($dataUser["idDistrict"]);
                $Region = $entityManager->getRepository(Region::class)->find($dataUser["idRegion"]);
                $rmaNut = new RmaNut();
                $rmaNut->setCommandeTrimestrielle($CommandeTrimestrielle);
                $rmaNut->setUploadedBy($user);
                $rmaNut->setUploadedDate(new \DateTime());
                $rmaNut->setOriginalFileName($file->getClientOriginalName());
                $rmaNut->setNewFileName($newFilename);
                $rmaNut->setDistrict($District);
                $rmaNut->setRegion($Region);

                // Persist data to the database using Doctrine
                $entityManager->persist($rmaNut);
                $entityManager->flush();

                $this->addFlash('success', '<strong>Fichier RMANut téléchargé avec succès</strong><br/>');
                return $this->redirectToRoute('app_rmanut');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/rmanut/update/{idCommandeTrimestrielle}', name: 'app_rmanut_updateFile')]
    public function updateFileRMANut(Request $request, EntityManagerInterface $entityManager, $idCommandeTrimestrielle)
    {
        $idRamanut = (int)$idCommandeTrimestrielle;

        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
        $user = $this->getUser(); // Supposons que cela retourne l'utilisateur actuellement connecté.

        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            // Récupérez le fichier téléversé depuis la requête.
            $file = $request->files->get('rmanut_file');
            $oldName = $request->files->get('hidNameFile');
            if ($file instanceof UploadedFile) {
                // Vérifiez l'extension du fichier (assurez-vous d'ajuster les extensions autorisées selon vos besoins).
                $allowedExtensions = ['xlsx', 'xls'];
                $extensionOriginalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                if (!in_array($extensionOriginalFilename, $allowedExtensions)) {
                    // Redirigez l'utilisateur avec un message d'erreur en cas d'extension non autorisée.
                    $this->addFlash('error', '<strong>Erreur de fichier téléverser</strong><br/> Le format accepté est le format <b>Microsoft Excel</b> au format <b>.xls</b> ou <b>.xlsx</b> .');
                    return $this->redirectToRoute('app_rmanut');
                }
                $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
                // Générez un nom de fichier unique en utilisant le format souhaité.
                $NomUser = preg_replace('/[^a-zA-Z0-9_]/', '', $dataUser["nomUser"]);
                $NomDistrict = strtolower($dataUser["nomDistrict"]);
                $NomCHU = preg_replace('/[^a-zA-Z0-9_]/', '', $dataUser["nomCHU"]);
                if ($NomCHU == "") {
                    $newFilename = 'RMA_NUT_' . $dataCommandeTrimestrielle["Slug"] . '_' . date('Ymd') . '_'  . $NomUser . '_' . $NomDistrict . '.' . $extensionOriginalFilename;
                } else {
                    $newFilename = 'RMA_NUT_' . $dataCommandeTrimestrielle["Slug"] . '_' . date('Ymd') . '_'  . $NomUser . '_' . $NomDistrict . '_' . $NomCHU . '.' . $extensionOriginalFilename;
                }
                // Déplacez le fichier téléversé vers le répertoire de destination (vous devrez configurer le répertoire dans votre configuration VichUploaderBundle).

                $mappingConfig = $this->getParameter('vich_uploader.mappings')['uploads_rmanut'];
                // Accédez au répertoire de destination depuis la configuration
                $uploadDestination = $mappingConfig['upload_destination'];
                $newFilePath = $uploadDestination . '/' . $newFilename;
                $oldFilePath = $uploadDestination . '/' . $oldName;
                if (file_exists($newFilePath)) {
                    // Supprimez le fichier existant.
                    unlink($newFilePath);
                }

                $file->move(
                    $uploadDestination, // Le répertoire de destination configuré dans VichUploader
                    $newFilename
                );

                if ($oldName != "") {
                    // Supprimez le fichier existant.
                    unlink($oldFilePath);
                }

                // Récupérez l'entité RmaNut à partir de la base de données en fonction de l'idCommandeTrimestrielle.
                $rmaNut = $entityManager->getRepository(RmaNut::class)->findOneBy(['id' => $idRamanut]);
                var_dump($idRamanut);

                if (!$rmaNut) {
                    $this->addFlash('error', 'Enregistrement non trouvé!');
                    var_dump("Enregistrement non trouvé");
                    var_dump($rmaNut);
                    die();
                    return $this->redirectToRoute('app_rmanut');
                } else {
                    // Mettez à jour les propriétés de l'entité avec les nouvelles informations du fichier.
                    $rmaNut->setUploadedDate(new \DateTime());
                    $rmaNut->setOriginalFileName($file->getClientOriginalName());
                    $rmaNut->setNewFileName($newFilename);
                    $rmaNut->setUploadedBy($user);
                    // Enregistrez les modifications dans la base de données.
                    $entityManager->flush();
                    // Ajoutez un message flash pour indiquer le succès de la mise à jour.
                    $this->addFlash('success', 'Fichier mis à jour avec succès!');
                }

                // Redirigez l'utilisateur vers la page d'accueil ou une autre page appropriée.
                return $this->redirectToRoute('app_rmanut');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
        // Si le fichier n'est pas téléversé avec succès, assurez-vous de renvoyer une réponse appropriée.
        return $this->redirectToRoute('app_rmanut');
    }

    #[Route('/supervisor/rmanut/region/{regionId}', name: 'app_sp_rmanut_region')]
    public function listeRmaNutRegion($regionId, EntityManagerInterface $entityManager)
    {
        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $lstRMANutRegion = $this->_rmaNutService->findListRMANutByRegion($regionId);
            $Region = $entityManager->getRepository(Region::class)->find($regionId);
            if (!$Region) {
                throw $this->createNotFoundException('La région n\'existe pas.');
            }
            // Obtenez le nombre total de districts dans la région
            $numberOfDistricts = $Region->getDistricts()->count();
            $districtsDataRmaNut = $this->_rmaNutService->getDistrictsDataRmaNutByRegion($regionId);

            return $this->render('supervisor/supervisorCentralRmaNutRegion.html.twig', [
                "mnuActive" => "RMANut",
                "dataUser" => $dataUser,
                "dataRegion" => $Region,
                "lstRMANutRegion" => $lstRMANutRegion,
                "numberOfDistrictSendRMANut" => count($lstRMANutRegion),
                "numberOfDistricts" => $numberOfDistricts,
                "districtsDataRmaNut" => $districtsDataRmaNut,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/supervisor/rmanut/region/upload/historique/{regionId}', name: 'app_sp_rmanut_region_export_historic')]
    public function exportExcelHistoricRmaNutRegion($regionId, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        if ($user) {

            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $lstRMANutRegion = $this->_rmaNutService->findListRMANutByRegion($regionId);
            $Region = $entityManager->getRepository(Region::class)->find($regionId);
            if (!$Region) {
                throw $this->createNotFoundException('La région n\'existe pas.');
            }

            $lstRMANutRegion = $this->_rmaNutService->findListRMANutByRegion($regionId);

            $arrNameFile = [];
            $indexRmanut = 0;
            $nameRegion = "";

            usort($lstRMANutRegion, function ($a, $b) {
                return $b['uploadedDate'] <=> $a['uploadedDate'];
            });

            $dataCommandeSemestrielle = $this->_commandeSemestrielleService->findDataCommandeSemestrielle();
            $dataMoisProjection = $this->_creniMoisProjectionAdmissionService->findDataMoisProjection($dataCommandeSemestrielle["idCommandeSemestrielle"]);

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

            $spreadsheet = new Spreadsheet();
            // Boucler sur chaque fichier RMA Nut du district
            foreach ($lstRMANutRegion as $rmaNut) {
                $fileNameRmaNut = $rmaNut["newFileName"];

                if (!isset($fileNameRmaNut) || empty($fileNameRmaNut)) {
                    continue;
                }

                $lstDataCrenasRegion =  $this->_dataCrenaService->findDataCrenasByDistrictId($rmaNut["districtId"]);
                // var_dump($lstDataCrenasRegion);
                // var_dump("+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
                // var_dump($lstMoisProjectionAnneePrevisionnelle);

                // Récupérer le chemin vers le fichier
                $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/RMANut/' . $fileNameRmaNut;

                if (file_exists($filePath)) {

                    try {
                        $reader = ReaderEntityFactory::createXLSXReader();
                        $reader->open($filePath);
                    } catch (\Box\Spout\Common\Exception\IOException | \Exception $e) {

                        // Vous pouvez enregistrer l'erreur dans un journal (logs)
                        $this->logger->info('Erreur lors de l\'ouverture du fichier Excel : ' . $e->getMessage());

                        continue;
                        // return $this->render('error/custom_error.html.twig', [
                        //     "mnuActive" => "RMANut",
                        //     'errorTitle' => "Erreur lors de l'ouverture du fichier Excel",
                        //     'errorMessage' => 'Le fichier Excel : <strong>"' . $fileNameRmaNut . '"</strong> n\'a pas pu être ouvert pour la raison suivante: ',
                        //     "exception" => $e
                        // ]);
                    }

                    // Obtenez l'itérateur de feuilles
                    $sheetIterator = $reader->getSheetIterator();

                    // --------------------------------- CRENAS ---------------------------------
                    $valuesLastCrenasTemp = [];
                    $valuesNowCrenasTemp = [];

                    $selectedSheetNameCRENAS = 'COMPILATION CSB';
                    $selectedSheetCRENAS = null;

                    try {
                        foreach ($sheetIterator as $sheet) {
                            if ($sheet->getName() === $selectedSheetNameCRENAS) {
                                $selectedSheetCRENAS = $sheet;
                                break;
                            }
                        }

                        // Vérifiez si l'onglet spécifié a été trouvé
                        if ($selectedSheetCRENAS !== null) {

                            // var_dump($selectedSheetCRENAS);
                            $currentRow = 1;
                            foreach ($selectedSheetCRENAS->getRowIterator() as $index => $row) {
                                if ($currentRow >= 22 && $currentRow <= 33) {
                                    // NB: Pour des raisons non encore éclaircies $currentRow = 22 à la ligne 28 et $currentRow = 33 à la ligne 39, un décalage de 6 lignes
                                    $columnLastCrenas = !empty($row->getCellAtIndex(13)->getValue()) ? $row->getCellAtIndex(13)->getValue() : null;
                                    $columnNowCrenas  = !empty($row->getCellAtIndex(3)->getValue()) ? $row->getCellAtIndex(3)->getValue() : null;
                                    // Vérifiez si la cellule est protégée
                                    // var_dump($row->getCellAtIndex(13));
                                    // var_dump($row->getCellAtIndex(3));
                                    $valuesLastCrenasTemp[] = $columnLastCrenas != 0 ? $columnLastCrenas : null;
                                    $valuesNowCrenasTemp[] = $columnNowCrenas != 0 ? $columnNowCrenas : null;
                                }
                                // Incrémentez le compteur de ligne
                                $currentRow++;
                                // Sortez de la boucle si on a atteint la fin de la plage de lignes souhaitée
                                if ($currentRow > 33) {
                                    break;
                                }
                            }
                        } else {
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }

                    // --------------------------------- CRENI ---------------------------------
                    $valuesLastCreniTemp = [];
                    $valuesNowCreniTemp = [];

                    $selectedSheetNameCRENI = 'CANEVAS SDSP';
                    $selectedSheetCRENI = null;
                    foreach ($sheetIterator as $sheet) {
                        if ($sheet->getName() === $selectedSheetNameCRENI) {
                            $selectedSheetCRENI = $sheet;
                            break;
                        }
                    }

                    // Vérifiez si l'onglet spécifié a été trouvé
                    if ($selectedSheetCRENI !== null) {
                        $currentRow = 1;
                        foreach ($selectedSheetCRENI->getRowIterator() as $index => $row) {
                            if ($currentRow >= 37 && $currentRow <= 48) {
                                // NB: Pour des raisons non encore éclairci $currentRow = 45 à la ligne 36 et $currentRow = 56 à la ligne 47 une décalage de 6 lignes
                                $columnLastCreni = $row->getCellAtIndex(11)->getValue();
                                $columnNowCreni  = $row->getCellAtIndex(2)->getValue();
                                $valuesLastCreniTemp[] = $columnLastCreni != 0 ? $columnLastCreni : null;
                                $valuesNowCreniTemp[] = $columnNowCreni != 0 ? $columnNowCreni : null;
                            }
                            // Incrémentez le compteur de ligne
                            $currentRow++;
                            // Sortez de la boucle si on a atteint la fin de la plage de lignes souhaitée
                            if ($currentRow > 50) {
                                break;
                            }
                        }
                    } else {
                        // Faites quelque chose si l'onglet spécifié n'existe pas
                        throw new \Exception('Sheet not found with the name: ' . $selectedSheetNameCRENI);
                    }

                    $reader->close();
                } else {
                    continue;
                }

                if (in_array($fileNameRmaNut, $arrNameFile)) {
                    continue;
                }

                $sheet = $spreadsheet->createSheet($indexRmanut);
                $truncatedTitle = substr($rmaNut['districtNom'], 0, 31);
                $sheet->setTitle($truncatedTitle);

                // Récupérer l'année courante
                $currentYear = date('Y');

                // Générer la séquence de dates pour l'année courante - 1, l'année courante, l'année courante + 1
                $years = [$currentYear - 2, $currentYear - 1, $currentYear];
                $row = 3;
                $col = "A";
                $sheet->setCellValue($col . ($row - 1), "Mois");
                $format = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_XLSX17;
                foreach ($years as $year) {
                    // Générer les dates pour chaque mois
                    for ($month = 1; $month <= 12; $month++) {
                        $date = sprintf('%04d-%02d-01', $year, $month);
                        $date = new \DateTime($date);
                        // Ajouter la date à la feuille de calcul
                        $sheet->setCellValue($col . $row,  \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($date->format('Y-m-d')));
                        $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode($format);
                        // Incrémenter le numéro de ligne
                        $row++;
                    }
                }

                $col = "B";
                $row = 2;
                $sheet->setCellValue($col . 1, "CRENAS");
                $sheet->setCellValue($col . $row, "REEL");
                $sheet->setCellValue($col . ($row + 1), $valuesLastCrenasTemp[0]);
                $sheet->setCellValue($col . ($row + 2), $valuesLastCrenasTemp[1]);
                $sheet->setCellValue($col . ($row + 3), $valuesLastCrenasTemp[2]);
                $sheet->setCellValue($col . ($row + 4), $valuesLastCrenasTemp[3]);
                $sheet->setCellValue($col . ($row + 5), $valuesLastCrenasTemp[4]);
                $sheet->setCellValue($col . ($row + 6), $valuesLastCrenasTemp[5]);
                $sheet->setCellValue($col . ($row + 7), $valuesLastCrenasTemp[6]);
                $sheet->setCellValue($col . ($row + 8), $valuesLastCrenasTemp[7]);
                $sheet->setCellValue($col . ($row + 9), $valuesLastCrenasTemp[8]);
                $sheet->setCellValue($col . ($row + 10), $valuesLastCrenasTemp[9]);
                $sheet->setCellValue($col . ($row + 11), $valuesLastCrenasTemp[10]);
                $sheet->setCellValue($col . ($row + 12), $valuesLastCrenasTemp[11]);
                $sheet->setCellValue($col . ($row + 13), $valuesNowCrenasTemp[0]);
                $sheet->setCellValue($col . ($row + 14), $valuesNowCrenasTemp[1]);
                $sheet->setCellValue($col . ($row + 15), $valuesNowCrenasTemp[2]);
                $sheet->setCellValue($col . ($row + 16), $valuesNowCrenasTemp[3]);
                $sheet->setCellValue($col . ($row + 17), $valuesNowCrenasTemp[4]);
                $sheet->setCellValue($col . ($row + 18), $valuesNowCrenasTemp[5]);
                $sheet->setCellValue($col . ($row + 19), $valuesNowCrenasTemp[6]);
                $sheet->setCellValue($col . ($row + 20), $valuesNowCrenasTemp[7]);
                $sheet->setCellValue($col . ($row + 21), $valuesNowCrenasTemp[8]);
                $sheet->setCellValue($col . ($row + 22), $valuesNowCrenasTemp[9]);
                $sheet->setCellValue($col . ($row + 23), $valuesNowCrenasTemp[10]);
                $sheet->setCellValue($col . ($row + 24), $valuesNowCrenasTemp[11]);

                $col = "C";
                $row = 2;
                $sheet->setCellValue($col . $row, "BC CRENAS");
                $lstDataCrenasRegion =  $this->_dataCrenaService->findDataCrenasByDistrictId($rmaNut["districtId"]);
                $dataAnneePrevisionnelle = $this->_anneePrevisionnelleService->findDataAnneePrevisionnelle();
                $dataRegion = $this->_regionService->findById($rmaNut["regionId"]);
                $idProvince = $dataRegion->getProvince()->getId();
                $dataGroupe = $this->_groupeService->findDataGroupe($dataAnneePrevisionnelle["IdAnneePrevisionnelle"], $idProvince, $rmaNut["districtId"]);
                $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
                $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($dataGroupe["idGroupe"], $dataCommandeTrimestrielle['idCommandeTrimestrielle']);

                $lstMoisProjectionAnneePrevisionnelle = array();
                if (isset($dataMoisProjection) && count($dataMoisProjection) > 0) {
                    for ($i = 0; $i < count($dataMoisProjection); $i++) {
                        $lstMoisProjectionAnneePrevisionnelle[] = $dataMoisProjection[$i]["MoisProjectionAnneePrevisionnelle"];
                    }
                }

                $lstValueMoisProjectionAnneePrevisionnelle = array();
                $valueDataCrenasReel = null;
                $userDataCrenas = null;
                if (isset($lstDataCrenasRegion) && is_array($lstDataCrenasRegion) && count($lstDataCrenasRegion) > 0) {
                    for ($j = 0; $j < count($lstDataCrenasRegion); $j++) {
                        $dataCrenasId = $lstDataCrenasRegion[$j]["id"];
                        $valueDataCrenasReel = $this->_dataCrenaService->findDataCrenasById($dataCrenasId);
                        $idUserDataCrenas = $valueDataCrenasReel["userId"];
                        $userDataCrenas = $this->_userService->findDataUser($idUserDataCrenas);
                        $idDistrictDataCrenas = $userDataCrenas["idDistrict"];

                        $valueDataMoisProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasId($dataCrenasId);

                        if (isset($valueDataMoisProjection) && count($valueDataMoisProjection) > 0) {
                            for ($i = 0; $i < count($valueDataMoisProjection); $i++) {
                                $lstValueMoisProjectionAnneePrevisionnelle[$idDistrictDataCrenas][] = $valueDataMoisProjection[$i]["DataMoisProjectionAnneePrevisionnelle"];
                            }
                        }
                    }
                }
                // var_dump(count($lstMoisProjectionAnneePrevisionnelle));
                // var_dump($lstMoisProjectionAnneePrevisionnelle);

                if (count($lstMoisProjectionAnneePrevisionnelle) > 0) {
                    $parts = explode(' ', $lstMoisProjectionAnneePrevisionnelle[0]);
                    $moisProjection = $parts[0];
                    $lastIndexMonth = 0;
                    for ($key = 0; $key < count($lstMoisProjectionAnneePrevisionnelle); $key++) {
                        if ($moisProjection == $lstMoisProjectionAnneePrevisionnelle[$key]) {
                            break;
                        }
                        $lastIndexMonth = $row + 25 + $key;
                    }

                    $arrValueBc = [];
                    $indexValueBc = 0;
                    foreach ($lstValueMoisProjectionAnneePrevisionnelle as $key => $value) {
                        if ($rmaNut["districtId"] == $key) {
                            for ($i = 0; $i < count($value); $i++) {
                                if (count($arrValueBc) > 2) {  // Le BC Crenas ne depasse pas 3 mois
                                    break;
                                }
                                $stringValue = !empty($value[$i]) ? $value[$i] : "";
                                $sheet->setCellValue($col . ($lastIndexMonth + $i + 1), $stringValue);
                                
                                $arrValueBc[] = $stringValue;
                                // var_dump($stringValue);
                                // var_dump($arrValueBc);
                            }
                        }
                    }
                }

                $col = "E";
                $row = 2;
                $sheet->setCellValue($col . 1, "CRENI");
                $sheet->setCellValue($col . $row, "REEL");
                $sheet->setCellValue($col . ($row + 1), $valuesLastCreniTemp[0]);
                $sheet->setCellValue($col . ($row + 2), $valuesLastCreniTemp[1]);
                $sheet->setCellValue($col . ($row + 3), $valuesLastCreniTemp[2]);
                $sheet->setCellValue($col . ($row + 4), $valuesLastCreniTemp[3]);
                $sheet->setCellValue($col . ($row + 5), $valuesLastCreniTemp[4]);
                $sheet->setCellValue($col . ($row + 6), $valuesLastCreniTemp[5]);
                $sheet->setCellValue($col . ($row + 7), $valuesLastCreniTemp[6]);
                $sheet->setCellValue($col . ($row + 8), $valuesLastCreniTemp[7]);
                $sheet->setCellValue($col . ($row + 9), $valuesLastCreniTemp[8]);
                $sheet->setCellValue($col . ($row + 10), $valuesLastCreniTemp[9]);
                $sheet->setCellValue($col . ($row + 11), $valuesLastCreniTemp[10]);
                $sheet->setCellValue($col . ($row + 12), $valuesLastCreniTemp[11]);
                $sheet->setCellValue($col . ($row + 13), $valuesNowCreniTemp[0]);
                $sheet->setCellValue($col . ($row + 14), $valuesNowCreniTemp[1]);
                $sheet->setCellValue($col . ($row + 15), $valuesNowCreniTemp[2]);
                $sheet->setCellValue($col . ($row + 16), $valuesNowCreniTemp[3]);
                $sheet->setCellValue($col . ($row + 17), $valuesNowCreniTemp[4]);
                $sheet->setCellValue($col . ($row + 18), $valuesNowCreniTemp[5]);
                $sheet->setCellValue($col . ($row + 19), $valuesNowCreniTemp[6]);
                $sheet->setCellValue($col . ($row + 20), $valuesNowCreniTemp[7]);
                $sheet->setCellValue($col . ($row + 21), $valuesNowCreniTemp[8]);
                $sheet->setCellValue($col . ($row + 22), $valuesNowCreniTemp[9]);
                $sheet->setCellValue($col . ($row + 23), $valuesNowCreniTemp[10]);
                $sheet->setCellValue($col . ($row + 24), $valuesNowCreniTemp[11]);

                $col = "F";
                $row = 2;
                $sheet->setCellValue($col . $row, "BC CRENI");
                foreach ($lstDataCreniRegion as $dataCreniRegion) {
                    if ($dataCreniRegion["nomDistrict"] == $rmaNut['districtNom']) {
                        $sheet->setCellValue($col . $row + 25, $dataCreniMoisProjetionAdmission[$dataCreniRegion["id"]]["DataMois01ProjectionAnneePrevisionnelle"]);
                        $sheet->setCellValue($col . $row + 26, $dataCreniMoisProjetionAdmission[$dataCreniRegion["id"]]["DataMois02ProjectionAnneePrevisionnelle"]);
                        $sheet->setCellValue($col . $row + 27, $dataCreniMoisProjetionAdmission[$dataCreniRegion["id"]]["DataMois03ProjectionAnneePrevisionnelle"]);
                        $sheet->setCellValue($col . $row + 28, $dataCreniMoisProjetionAdmission[$dataCreniRegion["id"]]["DataMois04ProjectionAnneePrevisionnelle"]);
                        $sheet->setCellValue($col . $row + 29, $dataCreniMoisProjetionAdmission[$dataCreniRegion["id"]]["DataMois05ProjectionAnneePrevisionnelle"]);
                        $sheet->setCellValue($col . $row + 30, $dataCreniMoisProjetionAdmission[$dataCreniRegion["id"]]["DataMois06ProjectionAnneePrevisionnelle"]);
                        break;
                    }
                }

                $arrNameFile[] = $fileNameRmaNut;
                $nameRegion = $rmaNut['regionNom'];
                $indexRmanut++;

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

                // Appliquer les styles de bordure
                for ($r = 1; $r <= $row + 36; $r++) {
                    for ($c = 1; $c <= 6; $c++) {
                        $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c) . $r;
                        $sheet->getStyle($cell)->applyFromArray($styleArray);
                    }
                }
            }
            // die('ok');

            // Créer un objet Writer pour sauvegarder le fichier Excel
            $writer = new Xlsx($spreadsheet);

            $fileName = "Historique_" . $nameRegion . "_" . date('d-m-Y Hi') . '.xlsx';

            // Chemin temporaire pour sauvegarder le fichier Excel
            $tempFilePath = sys_get_temp_dir() . '/' . $fileName;

            // Sauvegarder le fichier Excel sur le serveur
            $writer->save($tempFilePath);

            // Retourner le fichier en téléchargement
            return $this->file($tempFilePath, $fileName);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/rmanut/extract/historic/{responsableId}', name: 'app_rmanut_extract_historic_responsable_district')]
    public function extractHistoricData($responsableId)
    {
        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataDistrict = $this->_rmaNutService->getInfoDistrictWithRmaNutByUserId($responsableId);
         
            /* ---------------------------- DATA CRENAS ---------------------------- */
            $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
            $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($dataDistrict["groupeId"], $dataCommandeTrimestrielle['idCommandeTrimestrielle']);
            $lstMoisProjectionAnneePrevisionnelle = array();
            if (isset($dataMoisProjection) && count($dataMoisProjection) > 0) {
                for ($i = 0; $i < count($dataMoisProjection); $i++) {
                    $lstMoisProjectionAnneePrevisionnelle[] = $dataMoisProjection[$i]["MoisProjectionAnneePrevisionnelle"];
                }
            }
         
            $arrDataCrenasUser =  $this->_dataCrenaService->findDataCrenasByUserId($responsableId); 
            $isUserHavingDataValidationCrenas = false;
            $isUserHavingDataCrenas = false;
            if (isset($arrDataCrenasUser) && is_array($arrDataCrenasUser) && count($arrDataCrenasUser) > 0) {
                $isUserHavingDataCrenas = true;
                $valueDataMoisProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasId($arrDataCrenasUser["id"]);
                $lstValueValidatedDataMonth = $this->_dataValidationCrenasService->findDataValidationCrenasByCrenasId($arrDataCrenasUser["id"]);
                if (isset($lstValueValidatedDataMonth) && is_array($lstValueValidatedDataMonth) && count($lstValueValidatedDataMonth) > 0) {
                    $isUserHavingDataValidationCrenas = true;
                }
            } else {
                $valueDataMoisProjection = [];
                $lstValueValidatedDataMonth = [];
            }
          
            $lstValueMoisProjectionAnneePrevisionnelle = array();
            if (isset($valueDataMoisProjection) && count($valueDataMoisProjection) > 0) {
                for ($i = 0; $i < count($valueDataMoisProjection); $i++) {
                    $lstValueMoisProjectionAnneePrevisionnelle[$arrDataCrenasUser["id"]][] = $valueDataMoisProjection[$i]["DataMoisProjectionAnneePrevisionnelle"];
                }
            } 

            /* ---------------------------- DATA CRENI ---------------------------- */
            $arrDataCreniUser = $this->_dataCreniService->findDataCreniByUserId($responsableId);
            $dataCommandeSemestrielle = $this->_commandeSemestrielleService->findDataCommandeSemestrielle();
            $dataMoisProjection = $this->_creniMoisProjectionAdmissionService->findDataMoisProjection($dataCommandeSemestrielle["idCommandeSemestrielle"]);

            $isUserHavingDataCreni = false;
            $isUserHavingDataValidationCreni = false;
            $arrDataCreniUser = $this->_dataCreniService->findDataCreniByUserId($responsableId);
            if (isset($arrDataCreniUser) && is_array($arrDataCreniUser) && count($arrDataCreniUser) > 0) {
                $isUserHavingDataCreni = true;
                $dataCreniMoisProjetionAdmission = $this->_dataCreniMoisProjectionAdmissionService->findDataCreniMoisProjectionAdmissionByCreniIdAndMoisProjection($arrDataCreniUser["id"], $dataCommandeSemestrielle["idCommandeSemestrielle"]);
                $dataValueValidatedMonthCreni = $this->_dataValidationCreniService->findDataValidationCreniByCreniId($arrDataCreniUser["id"]);
                if (isset($dataValueValidatedMonthCreni) && is_array($dataValueValidatedMonthCreni) && count($dataValueValidatedMonthCreni) > 0) {
                    $isUserHavingDataValidationCreni = true;
                }
            } else {
                $dataCreniMoisProjetionAdmission = [];
                $dataValueValidatedMonthCreni = [];
            }
           

            $fileNameRmaNut = $dataDistrict["newFileName"];

            // Récupérer le chemin vers le fichier
            $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/RMANut/' . $fileNameRmaNut;
            // Vérifier si le fichier existe 
            if (file_exists($filePath)) {

                try {
                    $reader = ReaderEntityFactory::createXLSXReader();
                    $reader->open($filePath);
                } catch (\Box\Spout\Common\Exception\IOException | \Exception $e) {

                    // Vous pouvez enregistrer l'erreur dans un journal (logs)
                    $this->logger->info('Erreur lors de l\'ouverture du fichier Excel : ' . $e->getMessage());

                    return $this->render('error/custom_error.html.twig', [
                        "mnuActive" => "RMANut",
                        'errorTitle' => "Erreur lors de l'ouverture du fichier Excel",
                        'errorMessage' => 'Le fichier Excel : <strong>"' . $fileNameRmaNut . '"</strong> n\'a pas pu être ouvert pour la raison suivante: ',
                        "exception" => $e
                    ]);
                }

                // Obtenez l'itérateur de feuilles
                $sheetIterator = $reader->getSheetIterator();

                // --------------------------------- CRENAS ---------------------------------
                $valuesLastCrenas = [];
                $valuesNowCrenas = [];

                $selectedSheetNameCRENAS = 'COMPILATION CSB';
                $selectedSheetCRENAS = null;
                foreach ($sheetIterator as $sheet) {
                    if ($sheet->getName() === $selectedSheetNameCRENAS) {
                        $selectedSheetCRENAS = $sheet;
                        break;
                    }
                }

                // Vérifiez si l'onglet spécifié a été trouvé
                if ($selectedSheetCRENAS !== null) {
                    $currentRow = 1;
                    foreach ($selectedSheetCRENAS->getRowIterator() as $index => $row) {
                        if ($currentRow >= 22 && $currentRow <= 33) {
                            // NB: Pour des raisons non encore éclairci $currentRow = 22 à la ligne 28 et $currentRow = 33 à la ligne 39 une décalage de 6 lignes
                            $columnLastCrenas = $row->getCellAtIndex(13)->getValue();
                            $columnNowCrenas  = $row->getCellAtIndex(3)->getValue();
                            $valuesLastCrenas[] = $columnLastCrenas;
                            $valuesNowCrenas[] = $columnNowCrenas;
                        }
                        // Incrémentez le compteur de ligne
                        $currentRow++;
                        // Sortez de la boucle si on a atteint la fin de la plage de lignes souhaitée
                        if ($currentRow > 33) {
                            break;
                        }
                    }
                } else {
                    // Faites quelque chose si l'onglet spécifié n'existe pas
                    throw new \Exception('Sheet not found with the name: ' . $selectedSheetNameCRENAS);
                }

                // --------------------------------- CRENI ---------------------------------
                $valuesLastCreni = [];
                $valuesNowCreni = [];

                $selectedSheetNameCRENI = 'CANEVAS SDSP';
                $selectedSheetCRENI = null;
                foreach ($sheetIterator as $sheet) {
                    if ($sheet->getName() === $selectedSheetNameCRENI) {
                        $selectedSheetCRENI = $sheet;
                        break;
                    }
                }

                // Vérifiez si l'onglet spécifié a été trouvé
                if ($selectedSheetCRENI !== null) {
                    $currentRow = 1;
                    foreach ($selectedSheetCRENI->getRowIterator() as $index => $row) {
                        if ($currentRow >= 37 && $currentRow <= 48) {
                            // NB: Pour des raisons non encore éclairci $currentRow = 45 à la ligne 36 et $currentRow = 56 à la ligne 47 une décalage de 6 lignes
                            $columnLastCreni = $row->getCellAtIndex(11)->getValue();
                            $columnNowCreni  = $row->getCellAtIndex(2)->getValue();
                            $valuesLastCreni[] = $columnLastCreni;
                            $valuesNowCreni[] = $columnNowCreni;
                        }
                        // Incrémentez le compteur de ligne
                        $currentRow++;
                        // Sortez de la boucle si on a atteint la fin de la plage de lignes souhaitée
                        if ($currentRow > 50) {
                            break;
                        }
                    }
                } else {
                    // Faites quelque chose si l'onglet spécifié n'existe pas
                    throw new \Exception('Sheet not found with the name: ' . $selectedSheetNameCRENI);
                }

                $reader->close();
 
                $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
                $dataAnneePrevisionnelle = $this->_anneePrevisionnelleService->findDataAnneePrevisionnelle();
                $dataGroupe = $this->_groupeService->findDataGroupe($dataAnneePrevisionnelle["IdAnneePrevisionnelle"], $dataUser["provinceId"], $dataUser["idDistrict"]);

                $valeurCalculTheoriqueATPE = null;
                $valeurCalculTheoriqueAMOX = null;
                $valeurCalculTheoriqueFichePatient = null;
                $valeurCalculTheoriqueRegistre = null;
                $valeurCalculTheoriqueCarnetRapport = null;

                if (isset($dataCommandeTrimestrielle) && $dataCommandeTrimestrielle != NULL) {
                    if ($dataCommandeTrimestrielle["Slug"] == "T1" && $dataCommandeTrimestrielle["isActive"] == 1) { 
                        $valeurCalculTheoriqueATPE = $dataAnneePrevisionnelle["ValeurCalculTheoriqueATPE01"];
                        $valeurCalculTheoriqueAMOX = $dataAnneePrevisionnelle["ValeurCalculTheoriqueAMOX01"];
                        $valeurCalculTheoriqueFichePatient = $dataAnneePrevisionnelle["ValeurCalculTheoriqueFichePatient01"];
                        $valeurCalculTheoriqueRegistre = $dataAnneePrevisionnelle["ValeurCalculTheoriqueRegistre01"];
                        $valeurCalculTheoriqueCarnetRapport = $dataAnneePrevisionnelle["ValeurCalculTheoriqueCarnetRapport01"];
                    } else {
                        $valeurCalculTheoriqueATPE = $dataAnneePrevisionnelle["ValeurCalculTheoriqueATPE02"];
                        $valeurCalculTheoriqueAMOX = $dataAnneePrevisionnelle["ValeurCalculTheoriqueAMOX02"];
                        $valeurCalculTheoriqueFichePatient = $dataAnneePrevisionnelle["ValeurCalculTheoriqueFichePatient02"];
                        $valeurCalculTheoriqueRegistre = $dataAnneePrevisionnelle["ValeurCalculTheoriqueRegistre02"];
                        $valeurCalculTheoriqueCarnetRapport = $dataAnneePrevisionnelle["ValeurCalculTheoriqueCarnetRapport02"];
                    }
                }

                return $this->render('supervisor/supervisorCentralRmaNutHistoric.html.twig', [
                    "mnuActive" => "RMANut",
                    "responsableId" => $responsableId,
                    "dataUser" => $dataUser,
                    "district" => $dataDistrict,
                    "valuesLastCrenas" => $valuesLastCrenas,
                    "valuesNowCrenas" => $valuesNowCrenas,
                    "valuesLastCreni" => $valuesLastCreni,
                    "valuesNowCreni" => $valuesNowCreni,
                    "arrDataCrenasUser" => $arrDataCrenasUser,
                    "dataCrenas" => $arrDataCrenasUser,
                    "dataGroupe" => $dataGroupe,
                    "dataCommandeTrimestrielle" => $dataCommandeTrimestrielle,
                    "arrDataCreniUser" => $arrDataCreniUser,
                    "dataCreni" => $arrDataCreniUser,
                    "dataCommandeSemestrielle" => $dataCommandeSemestrielle,
                    "dataValueValidatedMonthCreni" => $dataValueValidatedMonthCreni,
                    "dataMoisProjection" => $dataMoisProjection,
                    "dataCreniMoisProjetionAdmission" => $dataCreniMoisProjetionAdmission,
                    "lstMoisProjectionAnneePrevisionnelle" => $lstMoisProjectionAnneePrevisionnelle,
                    "valueDataMoisProjection" => $valueDataMoisProjection,
                    "lstValueMoisProjectionAnneePrevisionnelle" => $lstValueMoisProjectionAnneePrevisionnelle,
                    "lstValueValidatedDataMonth" => $lstValueValidatedDataMonth,
                    "isUserHavingDataCrenas" => $isUserHavingDataCrenas,
                    "isUserHavingDataValidationCrenas" => $isUserHavingDataValidationCrenas,
                    "isUserHavingDataCreni" => $isUserHavingDataCreni,
                    "isUserHavingDataValidationCreni" => $isUserHavingDataValidationCreni,
                    "valeurCalculTheoriqueATPE" => $valeurCalculTheoriqueATPE,
                    "valeurCalculTheoriqueAMOX" => $valeurCalculTheoriqueAMOX,
                    "valeurCalculTheoriqueFichePatient" => $valeurCalculTheoriqueFichePatient,
                    "valeurCalculTheoriqueRegistre" => $valeurCalculTheoriqueRegistre,
                    "valeurCalculTheoriqueCarnetRapport" => $valeurCalculTheoriqueCarnetRapport
                ]);
            } else {
                return $this->render('error/custom_error.html.twig', [
                    "mnuActive" => "RMANut",
                    'errorTitle' => "Erreur, fichier non trouvé",
                    'errorMessage' => 'Le fichier Excel : <strong>"' . $fileNameRmaNut . '"</strong> n\'a pas été trouvé.',
                    "exception" => "File Not Found"
                ]);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/rmanut/historique/{responsableId}', name: 'app_rmanut_historique_district')]
    public function visualiserHistoriqueDistrict($responsableId)
    {
        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataDistrict = $this->_rmaNutService->getInfoDistrictWithRmaNutByUserId($responsableId);
            /* ---------------------------- DATA CRENAS ---------------------------- */
            $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
            $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($dataDistrict["groupeId"], $dataCommandeTrimestrielle['idCommandeTrimestrielle']);
            $lstMoisProjectionAnneePrevisionnelle = array();
            if (isset($dataMoisProjection) && count($dataMoisProjection) > 0) {
                for ($i = 0; $i < count($dataMoisProjection); $i++) {
                    $lstMoisProjectionAnneePrevisionnelle[] = $dataMoisProjection[$i]["MoisProjectionAnneePrevisionnelle"];
                }
            }

            $arrDataCrenasUser =  $this->_dataCrenaService->findDataCrenasByUserId($responsableId);
            if (isset($arrDataCrenasUser) && is_array($arrDataCrenasUser) && count($arrDataCrenasUser) > 0) {
                $valueDataMoisProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasId($arrDataCrenasUser["id"]);
            } else {
                $valueDataMoisProjection = [];
            }
            $lstValueMoisProjectionAnneePrevisionnelle = array();
            if (isset($valueDataMoisProjection) && count($valueDataMoisProjection) > 0) {
                for ($i = 0; $i < count($valueDataMoisProjection); $i++) {
                    $lstValueMoisProjectionAnneePrevisionnelle[$arrDataCrenasUser["id"]][] = $valueDataMoisProjection[$i]["DataMoisProjectionAnneePrevisionnelle"];
                }
            }

            /* ---------------------------- DATA CRENI ---------------------------- */
            $arrDataCreniUser = $this->_dataCreniService->findDataCreniByUserId($responsableId);
            $dataCommandeSemestrielle = $this->_commandeSemestrielleService->findDataCommandeSemestrielle();
            $dataMoisProjection = $this->_creniMoisProjectionAdmissionService->findDataMoisProjection($dataCommandeSemestrielle["idCommandeSemestrielle"]);
            $arrDataCreniUser = $this->_dataCreniService->findDataCreniByUserId($responsableId);
            if (isset($arrDataCreniUser) && is_array($arrDataCreniUser) && count($arrDataCreniUser) > 0) {
                $dataCreniMoisProjetionAdmission = $this->_dataCreniMoisProjectionAdmissionService->findDataCreniMoisProjectionAdmissionByCreniIdAndMoisProjection($arrDataCreniUser["id"], $dataCommandeSemestrielle["idCommandeSemestrielle"]);
            } else {
                $dataCreniMoisProjetionAdmission = [];
            }

            return $this->render('supervisor/historiqueAdmission.html.twig', [
                "dataUser" => $dataUser,
                "district" => $dataDistrict,
                "arrDataCrenasUser" => $arrDataCrenasUser,
                "arrDataCreniUser" => $arrDataCreniUser,
                "dataMoisProjection" => $dataMoisProjection,
                "dataCreniMoisProjetionAdmission" => $dataCreniMoisProjetionAdmission,
                "lstMoisProjectionAnneePrevisionnelle" => $lstMoisProjectionAnneePrevisionnelle,
                "valueDataMoisProjection" => $valueDataMoisProjection,
                "lstValueMoisProjectionAnneePrevisionnelle" => $lstValueMoisProjectionAnneePrevisionnelle
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/rmanut/historique/export/crenas', name: 'export_excel_historic_crenas_crenis', methods: ['GET', 'POST'])]
    public function exportExcelHistoricCrenasCrenis(Request $request)
    {
        if ($request->isMethod('POST')) {
            $mois1LastCrenas = (float) $request->request->get('mois1LastCrenas');
            $mois1NowCrenas = (float) $request->request->get('mois1NowCrenas');
            $mois2LastCrenas = (float) $request->request->get('mois2LastCrenas');
            $mois2NowCrenas = (float) $request->request->get('mois2NowCrenas');
            $mois3LastCrenas = (float) $request->request->get('mois3LastCrenas');
            $mois3NowCrenas = (float) $request->request->get('mois3NowCrenas');
            $mois4LastCrenas = (float) $request->request->get('mois4LastCrenas');
            $mois4NowCrenas = (float) $request->request->get('mois4NowCrenas');
            $mois5LastCrenas = (float) $request->request->get('mois5LastCrenas');
            $mois5NowCrenas = (float) $request->request->get('mois5NowCrenas');
            $mois6LastCrenas = (float) $request->request->get('mois6LastCrenas');
            $mois6NowCrenas = (float) $request->request->get('mois6NowCrenas');
            $mois7LastCrenas = (float) $request->request->get('mois7LastCrenas');
            $mois7NowCrenas = (float) $request->request->get('mois7NowCrenas');
            $mois8LastCrenas = (float) $request->request->get('mois8LastCrenas');
            $mois8NowCrenas = (float) $request->request->get('mois8NowCrenas');
            $mois9LastCrenas = (float) $request->request->get('mois9LastCrenas');
            $mois9NowCrenas = (float) $request->request->get('mois9NowCrenas');
            $mois10LastCrenas = (float) $request->request->get('mois10LastCrenas');
            $mois10NowCrenas = (float) $request->request->get('mois10NowCrenas');
            $mois11LastCrenas = (float) $request->request->get('mois11LastCrenas');
            $mois11NowCrenas = (float) $request->request->get('mois11NowCrenas');
            $mois12LastCrenas = (float) $request->request->get('mois12LastCrenas');
            $mois12NowCrenas = (float) $request->request->get('mois12NowCrenas');

            $mois1LastCreni = (float) $request->request->get('mois1LastCreni');
            $mois1NowCreni = (float) $request->request->get('mois1NowCreni');
            $mois2LastCreni = (float) $request->request->get('mois2LastCreni');
            $mois2NowCreni = (float) $request->request->get('mois2NowCreni');
            $mois3LastCreni = (float) $request->request->get('mois3LastCreni');
            $mois3NowCreni = (float) $request->request->get('mois3NowCreni');
            $mois4LastCreni = (float) $request->request->get('mois4LastCreni');
            $mois4NowCreni = (float) $request->request->get('mois4NowCreni');
            $mois5LastCreni = (float) $request->request->get('mois5LastCreni');
            $mois5NowCreni = (float) $request->request->get('mois5NowCreni');
            $mois6LastCreni = (float) $request->request->get('mois6LastCreni');
            $mois6NowCreni = (float) $request->request->get('mois6NowCreni');
            $mois7LastCreni = (float) $request->request->get('mois7LastCreni');
            $mois7NowCreni = (float) $request->request->get('mois7NowCreni');
            $mois8LastCreni = (float) $request->request->get('mois8LastCreni');
            $mois8NowCreni = (float) $request->request->get('mois8NowCreni');
            $mois9LastCreni = (float) $request->request->get('mois9LastCreni');
            $mois9NowCreni = (float) $request->request->get('mois9NowCreni');
            $mois10LastCreni = (float) $request->request->get('mois10LastCreni');
            $mois10NowCreni = (float) $request->request->get('mois10NowCreni');
            $mois11LastCreni = (float) $request->request->get('mois11LastCreni');
            $mois11NowCreni = (float) $request->request->get('mois11NowCreni');
            $mois12LastCreni = (float) $request->request->get('mois12LastCreni');
            $mois12NowCreni = (float) $request->request->get('mois12NowCreni');
            $Mois01ProjectionAnneePrevisionnelle = (float) $request->request->get('moisProjectionAnneePrevisionnelleCreni1');
            $Mois02ProjectionAnneePrevisionnelle = (float) $request->request->get('moisProjectionAnneePrevisionnelleCreni2');
            $Mois03ProjectionAnneePrevisionnelle = (float) $request->request->get('moisProjectionAnneePrevisionnelleCreni3');
            $Mois04ProjectionAnneePrevisionnelle = (float) $request->request->get('moisProjectionAnneePrevisionnelleCreni4');
            $Mois05ProjectionAnneePrevisionnelle = (float) $request->request->get('moisProjectionAnneePrevisionnelleCreni5');
            $Mois06ProjectionAnneePrevisionnelle = (float) $request->request->get('moisProjectionAnneePrevisionnelleCreni6');


            $hidDistrictName = $request->request->get('hidDistrictName');

            $nameMoisProjection = [];

            $moisProjectionAnneePrevisionnelle = [];
            for ($i = 1; $i <= 6; $i++) {
                if ($request->request->get('moisProjectionAnneePrevisionnelle' . $i) != null && $request->request->get('nameMoisProjectionAnneePrevisionnel' . $i) != "") {
                    $moisProjectionAnneePrevisionnelle[] = $request->request->get('moisProjectionAnneePrevisionnelle' . $i);
                    $nameMoisProjection[] = $request->request->get('nameMoisProjectionAnneePrevisionnel' . $i);
                }
            }

            // Créez une instance de PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $col = "A";
            $row = 2;
            $sheet->setCellValue($col . $row, "Mois");
            $sheet->setCellValue($col . ($row + 1), "janv-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 2), "févr-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 3), "mars-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 4), "avr-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 5), "mai-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 6), "juin-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 7), "juil-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 8), "août-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 9), "sept-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 10), "oct-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 11), "nov-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 12), "déc-" . substr(date('Y'), 2, date('Y')) - 1);

            $sheet->setCellValue($col . ($row + 13), "janv-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 14), "févr-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 15), "mars-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 16), "avr-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 17), "mai-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 18), "juin-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 19), "juil-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 20), "août-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 21), "sept-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 22), "oct-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 23), "nov-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 24), "déc-" . substr(date('Y'), 2, date('Y')));

            $arrayNameAllMonth = array("janv", "févr", "mars", "avr", "mai", "juin", "juil", "août", "sept", "oct", "nov", "déc");
            $arrayNameCompletAllMonth = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

            if (count($nameMoisProjection) > 0) {
                $parts = explode('_', $nameMoisProjection[0]);
                $moisProjection = $parts[0];
                $lastIndexMonth = 0;
                for ($key = 0; $key < count($arrayNameCompletAllMonth); $key++) {

                    if ($moisProjection == $arrayNameCompletAllMonth[$key]) {
                        break;
                    }

                    $lastIndexMonth = $row + 25 + $key;

                    $sheet->setCellValue($col . ($lastIndexMonth), $arrayNameAllMonth[$key] . "-" . substr(date('Y'), 2, date('Y')) + 1);
                }

                $colBcCrenas = "I";
                for ($key = 0; $key < count($nameMoisProjection); $key++) {
                    $parts = explode('_', $nameMoisProjection[$key]);
                    $moisProjectiontemp = self::convertirMois($parts[0]);
                    $sheet->setCellValue($col . ($lastIndexMonth + $key + 1), $moisProjectiontemp . "-" . substr(date('Y'), 2, date('Y')) + 1);
                    $sheet->setCellValue($colBcCrenas . ($lastIndexMonth + $key + 1), $moisProjectionAnneePrevisionnelle[$key]);
                }
            }

            $col = "B";
            $row = 2;
            $sheet->setCellValue($col . 1, "CRENAS");
            $sheet->setCellValue($col . $row, "REEL");
            $sheet->setCellValue($col . ($row + 1), $mois1LastCrenas);
            $sheet->setCellValue($col . ($row + 2), $mois2LastCrenas);
            $sheet->setCellValue($col . ($row + 3), $mois3LastCrenas);
            $sheet->setCellValue($col . ($row + 4), $mois4LastCrenas);
            $sheet->setCellValue($col . ($row + 5), $mois5LastCrenas);
            $sheet->setCellValue($col . ($row + 6), $mois6LastCrenas);
            $sheet->setCellValue($col . ($row + 7), $mois7LastCrenas);
            $sheet->setCellValue($col . ($row + 8), $mois8LastCrenas);
            $sheet->setCellValue($col . ($row + 9), $mois9LastCrenas);
            $sheet->setCellValue($col . ($row + 10), $mois10LastCrenas);
            $sheet->setCellValue($col . ($row + 11), $mois11LastCrenas);
            $sheet->setCellValue($col . ($row + 12), $mois12LastCrenas);
            $sheet->setCellValue($col . ($row + 13), $mois1NowCrenas);
            $sheet->setCellValue($col . ($row + 14), $mois2NowCrenas);
            $sheet->setCellValue($col . ($row + 15), $mois3NowCrenas);
            $sheet->setCellValue($col . ($row + 16), $mois4NowCrenas);
            $sheet->setCellValue($col . ($row + 17), $mois5NowCrenas);
            $sheet->setCellValue($col . ($row + 18), $mois6NowCrenas);
            $sheet->setCellValue($col . ($row + 19), $mois7NowCrenas);
            $sheet->setCellValue($col . ($row + 20), $mois8NowCrenas);
            $sheet->setCellValue($col . ($row + 21), $mois9NowCrenas);
            $sheet->setCellValue($col . ($row + 22), $mois10NowCrenas);
            $sheet->setCellValue($col . ($row + 23), $mois11NowCrenas);
            $sheet->setCellValue($col . ($row + 24), $mois12NowCrenas);

            $col = "E";
            $row = 2;
            $sheet->setCellValue($col . $row, "Mois");
            $sheet->setCellValue($col . ($row + 1), "janv-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 2), "févr-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 3), "mars-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 4), "avr-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 5), "mai-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 6), "juin-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 7), "juil-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 8), "août-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 9), "sept-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 10), "oct-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 11), "nov-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 12), "déc-" . substr(date('Y'), 2, date('Y')) - 1);
            $sheet->setCellValue($col . ($row + 13), "janv-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 14), "févr-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 15), "mars-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 16), "avr-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 17), "mai-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 18), "juin-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 19), "juil-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 20), "août-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 21), "sept-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 22), "oct-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 23), "nov-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 24), "déc-" . substr(date('Y'), 2, date('Y')));
            $sheet->setCellValue($col . ($row + 25), "janv-" . substr(date('Y'), 2, date('Y')) + 1);
            $sheet->setCellValue($col . ($row + 26), "févr-" . substr(date('Y'), 2, date('Y')) + 1);
            $sheet->setCellValue($col . ($row + 27), "mars-" . substr(date('Y'), 2, date('Y')) + 1);
            $sheet->setCellValue($col . ($row + 28), "avr-" . substr(date('Y'), 2, date('Y')) + 1);
            $sheet->setCellValue($col . ($row + 29), "mai-" . substr(date('Y'), 2, date('Y')) + 1);
            $sheet->setCellValue($col . ($row + 30), "juin-" . substr(date('Y'), 2, date('Y')) + 1);

            $col = "K"; // BC CRENI
            $sheet->setCellValue($col . ($row + 25), $Mois01ProjectionAnneePrevisionnelle);
            $sheet->setCellValue($col . ($row + 26), $Mois02ProjectionAnneePrevisionnelle);
            $sheet->setCellValue($col . ($row + 27), $Mois03ProjectionAnneePrevisionnelle);
            $sheet->setCellValue($col . ($row + 28), $Mois04ProjectionAnneePrevisionnelle);
            $sheet->setCellValue($col . ($row + 29), $Mois05ProjectionAnneePrevisionnelle);
            $sheet->setCellValue($col . ($row + 30), $Mois06ProjectionAnneePrevisionnelle);

            $col = "F";
            $row = 2;
            $sheet->setCellValue($col . 1, "CRENI");
            $sheet->setCellValue($col . $row, "REEL");
            $sheet->setCellValue($col . ($row + 1), $mois1LastCreni);
            $sheet->setCellValue($col . ($row + 2), $mois2LastCreni);
            $sheet->setCellValue($col . ($row + 3), $mois3LastCreni);
            $sheet->setCellValue($col . ($row + 4), $mois4LastCreni);
            $sheet->setCellValue($col . ($row + 5), $mois5LastCreni);
            $sheet->setCellValue($col . ($row + 6), $mois6LastCreni);
            $sheet->setCellValue($col . ($row + 7), $mois7LastCreni);
            $sheet->setCellValue($col . ($row + 8), $mois8LastCreni);
            $sheet->setCellValue($col . ($row + 9), $mois9LastCreni);
            $sheet->setCellValue($col . ($row + 10), $mois10LastCreni);
            $sheet->setCellValue($col . ($row + 11), $mois11LastCreni);
            $sheet->setCellValue($col . ($row + 12), $mois12LastCreni);
            $sheet->setCellValue($col . ($row + 13), $mois1NowCreni);
            $sheet->setCellValue($col . ($row + 14), $mois2NowCreni);
            $sheet->setCellValue($col . ($row + 15), $mois3NowCreni);
            $sheet->setCellValue($col . ($row + 16), $mois4NowCreni);
            $sheet->setCellValue($col . ($row + 17), $mois5NowCreni);
            $sheet->setCellValue($col . ($row + 18), $mois6NowCreni);
            $sheet->setCellValue($col . ($row + 19), $mois7NowCreni);
            $sheet->setCellValue($col . ($row + 20), $mois8NowCreni);
            $sheet->setCellValue($col . ($row + 21), $mois9NowCreni);
            $sheet->setCellValue($col . ($row + 22), $mois10NowCreni);
            $sheet->setCellValue($col . ($row + 23), $mois11NowCreni);
            $sheet->setCellValue($col . ($row + 24), $mois12NowCreni);

            $col = "I";
            $row = 2;
            $sheet->setCellValue($col . $row, "BC CRENAS");

            $col = "K";
            $row = 2;
            $sheet->setCellValue($col . $row, "BC CRENI");

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];

            // Appliquer les styles de bordure
            for ($r = 1; $r <= $row + 30; $r++) {
                for ($c = 1; $c <= 11; $c++) {
                    $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c) . $r;
                    $sheet->getStyle($cell)->applyFromArray($styleArray);
                }
            }

            // Créer un objet Writer pour sauvegarder le fichier Excel
            $writer = new Xlsx($spreadsheet);

            $fileName = 'Historique' . '_admissions_crenas_creni_' . $hidDistrictName . "_" . date('d-m-Y Hi') . '.xlsx';

            // Chemin temporaire pour sauvegarder le fichier Excel
            $tempFilePath = sys_get_temp_dir() . '/' . $fileName;

            // Sauvegarder le fichier Excel sur le serveur
            $writer->save($tempFilePath);

            // Retourner le fichier en téléchargement
            return $this->file($tempFilePath, $fileName);
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

    /**
     * Convertir le mois complet en version abrégée
     * @param string $moisComplet
     * @return string
     */
    function convertirMois($moisComplet)
    {
        $correspondanceMois = [
            'Janvier' => 'janv',
            'Février' => 'févr',
            'Mars' => 'mars',
            'Avril' => 'avr',
            'Mai' => 'mai',
            'Juin' => 'juin',
            'Juillet' => 'juil',
            'Août' => 'août',
            'Septembre' => 'sept',
            'Octobre' => 'oct',
            'Novembre' => 'nov',
            'Décembre' => 'déc'
        ];

        return $correspondanceMois[$moisComplet];
    }
}
