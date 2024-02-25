<?php

namespace App\Controller;
 
use App\Entity\CreniMoisProjectionsAdmissions;
use App\Entity\DataCreni;
use App\Entity\DataCreniMoisProjectionAdmission;
use App\Entity\DataValidationCreni;
use App\Entity\Region;
use App\Finder\CommandeSemestrielleFinder;
use App\Finder\CommandeTrimestrielleFinder;
use App\Finder\CreniMoisProjectionAdmissionFinder;
use App\Finder\DataCreniFinder;
use App\Finder\DataCreniMoisProjectionAdmissionFinder;
use App\Finder\DataValidationCreniFinder;
use App\Finder\GroupeFinder;
use App\Finder\RmaNutFinder;
use App\Finder\UserFinder;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CreniController extends AbstractController
{

    private $_userService; 
    private $_commandeTrimestrielleService;
    private $_commandeSemestrielleService;
    private $_rmaNutService;  
    private $_creniMoisProjectionAdmissionService;
    private $_dataCreniService;
    private $_dataCreniMoisProjectionAdmissionService;
    private $_dataValidationCreniService;

    public function __construct(UserFinder $user_service_container, RmaNutFinder $rma_nut, GroupeFinder $groupe_container, CommandeTrimestrielleFinder $commande_trimestrielle_container, CommandeSemestrielleFinder $commande_semestrielle_container, CreniMoisProjectionAdmissionFinder $creni_mois_projection_admission_container, DataCreniFinder $data_creni_container,DataValidationCreniFinder $data_validation_creni_container, DataCreniMoisProjectionAdmissionFinder $data_creni_mois_projection_admission_container)
    {
        $this->_userService = $user_service_container;   
        $this->_rmaNutService = $rma_nut;
        $this->_groupeService = $groupe_container;
        $this->_commandeTrimestrielleService = $commande_trimestrielle_container;
        $this->_commandeSemestrielleService = $commande_semestrielle_container;
        $this->_creniMoisProjectionAdmissionService = $creni_mois_projection_admission_container;
        $this->_dataCreniService = $data_creni_container;
        $this->_dataCreniMoisProjectionAdmissionService = $data_creni_mois_projection_admission_container;
        $this->_dataValidationCreniService = $data_validation_creni_container;
    }
    
    #[Route('/creni', name: 'app_creni')]
    public function index(Security $security): Response
    {
         // Vérifie si un utilisateur est connecté
         $user = $security->getUser(); 
         if ($user) {
            // L'utilisateur est connecté, obtenez son ID
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);  

            $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
            $dataCommandeSemestrielle = $this->_commandeSemestrielleService->findDataCommandeSemestrielle(); 
            $dataMoisProjection = $this->_creniMoisProjectionAdmissionService->findDataMoisProjection($dataCommandeSemestrielle["idCommandeSemestrielle"]);
            $dataRMANut = $this->_rmaNutService->findDataRmaNutByUserCommandeTrimestrielle($userId, $dataCommandeTrimestrielle['idCommandeTrimestrielle']);
            
            // Verifier si l'utilisateur a déjà renseigner son donnée CRENAS
            $isUserHavingDataCreni = false;

            if ($dataRMANut == NULL) {
                return $this->render('creni/homeCreni.html.twig', [
                    "dataUser" => $dataUser,
                    "dataRMANut" => $dataRMANut,
                    "dataMoisProjection" => $dataMoisProjection,
                    "isUserHavingDataCreni" => $isUserHavingDataCreni, 
                    "dataCommandeSemestrielle" => $dataCommandeSemestrielle,
                ]); 
            } else {

                $dataCreni = $this->_dataCreniService->findDataCreniByUserId($userId); 
                $dataCreniMoisProjetionAdmission = null;
                if (isset($dataCreni) && is_array($dataCreni) && count($dataCreni) > 0) {
                    $isUserHavingDataCreni = true;
                    $dataCreniMoisProjetionAdmission = $this->_dataCreniMoisProjectionAdmissionService->findDataCreniMoisProjectionAdmissionByCreniIdAndMoisProjection($dataCreni["id"], $dataCommandeSemestrielle["idCommandeSemestrielle"]);
                }
                 
                return $this->render('creni/homeCreni.html.twig', [
                    "dataUser" => $dataUser,
                    "dataRMANut" => $dataRMANut,
                    "dataMoisProjection" => $dataMoisProjection,
                    "isUserHavingDataCreni" => $isUserHavingDataCreni,
                    "dataCreni" => $dataCreni, 
                    "dataCommandeSemestrielle" => $dataCommandeSemestrielle,
                    "dataCreniMoisProjetionAdmission" => $dataCreniMoisProjetionAdmission
                ]); 
                
            } 
         } else { 
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/creni/save', name: 'app_creni_save', methods: ['GET', 'POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $totalAdmissionCreniSemestrePrecedent = (float) $request->request->get('totalAdmissionCreniSemestrePrecedent');
            $totalAdmissionCreniProjetePrecedent = (float) $request->request->get('totalAdmissionCreniProjetePrecedent');
            $ResultatDifferenceAdmissionPrecedent = (float) $request->request->get('ResultatDifferenceAdmissionPrecedent');
            $totalAdmissionCreniProjeterProchain = (float) $request->request->get('totalAdmissionCreniProjeterProchain');
            $ResultatDifferenceAdmissionProchainPrecedent = (float) $request->request->get('ResultatDifferenceAdmissionProchainPrecedent');

            // DEBUT: F75 - F100 - ReSoMal - PN - Fiche suivi CRENI - Fiche de suivi intensif - kIT
            $f75Boites = (float) $request->request->get('f75Boites');
            $f100Boites = (float) $request->request->get('f100Boites');
            $ReSoMalSachet = (float) $request->request->get('ReSoMalSachet');
            $pnSachet = (float) $request->request->get('pnSachet');
            $ficheSuiviCreni = (float) $request->request->get('ficheSuiviCreni');
            $ficheSuiviIntensif = (float) $request->request->get('ficheSuiviIntensif');
            $kitMedicamentsCreni10Patients = (float) $request->request->get('kitMedicamentsCreni10Patients');
            $registreCreni = (float) $request->request->get('registreCreni');
            $carnetRapportMensuelCreni = (float) $request->request->get('carnetRapportMensuelCreni');
            // FIN: F75 - F100 - ReSoMal - PN - Fiche suivi CRENI - Fiche de suivi intensif - kIT

            // DEBUT :  KIT CRENI
            $kitCreniAmoxici = (float) $request->request->get('kitCreniAmoxici');
            $kitCreniNystatin = (float) $request->request->get('kitCreniNystatin');
            $kitCreniFluconazole = (float) $request->request->get('kitCreniFluconazole');
            $kitCreniCiprofloxacin = (float) $request->request->get('kitCreniCiprofloxacin');
            $kitCreniAmpicillinpdr = (float) $request->request->get('kitCreniAmpicillinpdr');
            $kitCreniGentamicininj = (float) $request->request->get('kitCreniGentamicininj');
            $kitCreniSod = (float) $request->request->get('kitCreniSod');
            $kitCreniGlucoseInj = (float) $request->request->get('kitCreniGlucoseInj');
            $kitCreniGlucoseHypertonInj = (float) $request->request->get('kitCreniGlucoseHypertonInj');
            $kitCreniFurosemideinj = (float) $request->request->get('kitCreniFurosemideinj');
            $kitCreniChlorhexidine = (float) $request->request->get('kitCreniChlorhexidine');
            $kitCreniMiconazole = (float) $request->request->get('kitCreniMiconazole');
            $kitCreniTetracyclineeyeointment = (float) $request->request->get('kitCreniTetracyclineeyeointment');
            $kitCreniTubeFeeding = (float) $request->request->get('kitCreniTubeFeeding');
            $kitCreniTubeFeedingCH05 = (float) $request->request->get('kitCreniAmoxici');
            $kitCreniSyringeDisp2ml = (float) $request->request->get('kitCreniSyringeDisp2ml');
            $kitCreniSyringeDisp10ml = (float) $request->request->get('kitCreniSyringeDisp10ml');
            $kitCreniSyringeDisp20ml = (float) $request->request->get('kitCreniSyringeDisp20ml');
            $kitCreniSyringeDisp50ml = (float) $request->request->get('kitCreniSyringeDisp50ml'); 
            // FIN :  KIT CRENI

            // DEBUT :  SDU par produits
            $sduF75Boites = (float) $request->request->get('sduF75Boites');
            $sduF100Boites = (float) $request->request->get('sduF100Boites');
            $sduReSoMal = (float) $request->request->get('sduReSoMal');
            $sduPnSachet = (float) $request->request->get('sduPnSachet');
            $sduFicheSuiviCreni = (float) $request->request->get('sduFicheSuiviCreni');
            $sduFicheSuiviIntensif = (float) $request->request->get('sduFicheSuiviIntensif');
            $sduAmoxiciPdr = (float) $request->request->get('sduAmoxiciPdr');
            $sduNystatinOral = (float) $request->request->get('sduNystatinOral');
            $sduFluconazole50mg = (float) $request->request->get('sduFluconazole50mg');
            $sduAmpicillinpdrInj500mg = (float) $request->request->get('sduAmpicillinpdrInj500mg');
            $sduGentamicininj40mg = (float) $request->request->get('sduGentamicininj40mg');
            $sduSodLactatInj500ml = (float) $request->request->get('sduSodLactatInj500ml');
            $sduGlucoseInj500ml = (float) $request->request->get('sduGlucoseInj500ml');
            $sduGlucoseHyperton50ml = (float) $request->request->get('sduGlucoseHyperton50ml');
            $sduFurosemideinj10mg = (float) $request->request->get('sduFurosemideinj10mg');
            $sduChlorhexidineConSol = (float) $request->request->get('sduChlorhexidineConSol');
            $sduMiconazoleNitrate = (float) $request->request->get('sduMiconazoleNitrate');
            $sduTetracyclineeyeointment = (float) $request->request->get('sduTetracyclineeyeointment');
            $sduTubeFeedingCH08 = (float) $request->request->get('sduTubeFeedingCH08');
            $sduTubeFeedingCH05 = (float) $request->request->get('sduTubeFeedingCH05');
            $sduSyringeDisp2ml = (float) $request->request->get('sduSyringeDisp2ml');
            $sduSyringeDisp10ml = (float) $request->request->get('sduSyringeDisp10ml');
            $sduSyringeDisp20ml = (float) $request->request->get('sduSyringeDisp20ml');
            $sduSyringeFeeding50ml = (float) $request->request->get('sduSyringeFeeding50ml');
            $sduCiprofloxacin250mg = (float) $request->request->get('sduCiprofloxacin250mg');
            // FIN :  SDU par produits

            $isUserHavingDataCreni = $request->request->get('isUserHavingDataCreni');
            if ($isUserHavingDataCreni == 1) { // Edit an entity
                $DataCreniId =  $request->request->get('DataCreniId');
                $dataCreniEnity = $entityManager->getRepository(DataCreni::class)->find($DataCreniId); 
            } else { // Create an entity 
                $dataCreniEnity = new DataCreni();
                $user = $this->getUser();
                $dataCreniEnity->setUser($user);
            } 

            $dataCreniEnity->setTotalAdmissionCreniSemestrePrecedent($totalAdmissionCreniSemestrePrecedent);
            $dataCreniEnity->setTotalAdmissionCreniProjetePrecedent($totalAdmissionCreniProjetePrecedent);
            $dataCreniEnity->setResultatDifferenceAdmissionPrecedent($ResultatDifferenceAdmissionPrecedent);
            $dataCreniEnity->setTotalAdmissionCreniProjeterProchain($totalAdmissionCreniProjeterProchain);
            $dataCreniEnity->setResultatDifferenceAdmissionProchainPrecedent($ResultatDifferenceAdmissionProchainPrecedent);

            $dataCreniEnity->setF75Boites($f75Boites);
            $dataCreniEnity->setF100Boites($f100Boites);
            $dataCreniEnity->setReSoMalSachet($ReSoMalSachet);
            $dataCreniEnity->setPnSachet($pnSachet);
            $dataCreniEnity->setFicheSuiviCreni($ficheSuiviCreni);
            $dataCreniEnity->setFicheSuiviIntensif($ficheSuiviIntensif);
            $dataCreniEnity->setKitMedicamentsCreni10Patients($kitMedicamentsCreni10Patients); 
            $dataCreniEnity->setRegistreCreni($registreCreni);
            $dataCreniEnity->setCarnetRapportMensuelCreni($carnetRapportMensuelCreni);

            $dataCreniEnity->setKitCreniAmoxici($kitCreniAmoxici);
            $dataCreniEnity->setKitCreniNystatin($kitCreniNystatin);
            $dataCreniEnity->setKitCreniFluconazole($kitCreniFluconazole);
            $dataCreniEnity->setKitCreniCiprofloxacin($kitCreniCiprofloxacin);
            $dataCreniEnity->setKitCreniAmpicillinpdr($kitCreniAmpicillinpdr);
            $dataCreniEnity->setKitCreniGentamicininj($kitCreniGentamicininj);
            $dataCreniEnity->setKitCreniSod($kitCreniSod);
            $dataCreniEnity->setKitCreniGlucoseInj($kitCreniGlucoseInj);
            $dataCreniEnity->setKitCreniGlucoseHypertonInj($kitCreniGlucoseHypertonInj);
            $dataCreniEnity->setKitCreniFurosemideinj($kitCreniFurosemideinj);
            $dataCreniEnity->setKitCreniChlorhexidine($kitCreniChlorhexidine);
            $dataCreniEnity->setKitCreniMiconazole($kitCreniMiconazole);
            $dataCreniEnity->setKitCreniTetracyclineeyeointment($kitCreniTetracyclineeyeointment);
            $dataCreniEnity->setKitCreniTubeFeeding($kitCreniTubeFeeding);
            $dataCreniEnity->setKitCreniTubeFeedingCH05($kitCreniTubeFeedingCH05);
            $dataCreniEnity->setKitCreniSyringeDisp2ml($kitCreniSyringeDisp2ml);
            $dataCreniEnity->setKitCreniSyringeDisp10ml($kitCreniSyringeDisp10ml);
            $dataCreniEnity->setKitCreniSyringeDisp20ml($kitCreniSyringeDisp20ml);
            $dataCreniEnity->setKitCreniSyringeDisp50ml($kitCreniSyringeDisp50ml);

            $dataCreniEnity->setSduF75Boites($sduF75Boites);
            $dataCreniEnity->setSduF100Boites($sduF100Boites);
            $dataCreniEnity->setSduReSoMal($sduReSoMal);
            $dataCreniEnity->setSduPnSachet($sduPnSachet);
            $dataCreniEnity->setSduFicheSuiviCreni($sduFicheSuiviCreni);
            $dataCreniEnity->setSduFicheSuiviIntensif($sduFicheSuiviIntensif);
            $dataCreniEnity->setSduAmoxiciPdr($sduAmoxiciPdr);
            $dataCreniEnity->setSduNystatinOral($sduNystatinOral);
            $dataCreniEnity->setSduFluconazole50mg($sduFluconazole50mg);
            $dataCreniEnity->setSduAmpicillinpdrInj500mg($sduAmpicillinpdrInj500mg);
            $dataCreniEnity->setSduGentamicininj40mg($sduGentamicininj40mg);
            $dataCreniEnity->setSduSodLactatInj500ml($sduSodLactatInj500ml);
            $dataCreniEnity->setSduGlucoseInj500ml($sduGlucoseInj500ml);
            $dataCreniEnity->setSduGlucoseHyperton50ml($sduGlucoseHyperton50ml);
            $dataCreniEnity->setSduFurosemideinj10mg($sduFurosemideinj10mg);
            $dataCreniEnity->setSduChlorhexidineConSol($sduChlorhexidineConSol);
            $dataCreniEnity->setSduMiconazoleNitrate($sduMiconazoleNitrate);
            $dataCreniEnity->setSduTetracyclineeyeointment($sduTetracyclineeyeointment);
            $dataCreniEnity->setSduTubeFeedingCH08($sduTubeFeedingCH08);
            $dataCreniEnity->setSduTubeFeedingCH05($sduTubeFeedingCH05);
            $dataCreniEnity->setSduSyringeDisp2ml($sduSyringeDisp2ml);
            $dataCreniEnity->setSduSyringeDisp10ml($sduSyringeDisp10ml);
            $dataCreniEnity->setSduSyringeDisp20ml($sduSyringeDisp20ml);
            $dataCreniEnity->setSduSyringeFeeding50ml($sduSyringeFeeding50ml);
            $dataCreniEnity->setSduCiprofloxacin250mg($sduCiprofloxacin250mg); 

            // Persist data to the database using Doctrine
            //dump($dataCrenasEnity);dd();
            $entityManager->persist($dataCreniEnity);
            $entityManager->flush(); 
            
            $DataMois01AdmissionCreniPrecedent = (float) $request->request->get('DataMois01AdmissionCreniPrecedent');
            $DataMois02AdmissionCreniPrecedent = (float) $request->request->get('DataMois02AdmissionCreniPrecedent');
            $DataMois03AdmissionCreniPrecedent = (float) $request->request->get('DataMois03AdmissionCreniPrecedent');
            $DataMois04AdmissionCreniPrecedent = (float) $request->request->get('DataMois04AdmissionCreniPrecedent');
            $DataMois05AdmissionCreniPrecedent = (float) $request->request->get('DataMois05AdmissionCreniPrecedent');
            $DataMois06AdmissionCreniPrecedent = (float) $request->request->get('DataMois06AdmissionCreniPrecedent');

            $DataMois01AdmissionProjeteAnneePrecedent = (float) $request->request->get('DataMois01AdmissionProjeteAnneePrecedent');
            $DataMois02AdmissionProjeteAnneePrecedent = (float) $request->request->get('DataMois02AdmissionProjeteAnneePrecedent');
            $DataMois03AdmissionProjeteAnneePrecedent = (float) $request->request->get('DataMois03AdmissionProjeteAnneePrecedent');
            $DataMois04AdmissionProjeteAnneePrecedent = (float) $request->request->get('DataMois04AdmissionProjeteAnneePrecedent');
            $DataMois05AdmissionProjeteAnneePrecedent = (float) $request->request->get('DataMois05AdmissionProjeteAnneePrecedent');
            $DataMois06AdmissionProjeteAnneePrecedent = (float) $request->request->get('DataMois06AdmissionProjeteAnneePrecedent');

            $DataMois01ProjectionAnneePrevisionnelle = (float) $request->request->get('DataMois01ProjectionAnneePrevisionnelle');
            $DataMois02ProjectionAnneePrevisionnelle = (float) $request->request->get('DataMois02ProjectionAnneePrevisionnelle');
            $DataMois03ProjectionAnneePrevisionnelle = (float) $request->request->get('DataMois03ProjectionAnneePrevisionnelle');
            $DataMois04ProjectionAnneePrevisionnelle = (float) $request->request->get('DataMois04ProjectionAnneePrevisionnelle');
            $DataMois05ProjectionAnneePrevisionnelle = (float) $request->request->get('DataMois05ProjectionAnneePrevisionnelle');
            $DataMois06ProjectionAnneePrevisionnelle = (float) $request->request->get('DataMois06ProjectionAnneePrevisionnelle');


            $DataCommandeSemestrielleId = (int) $request->request->get('DataCommandeSemestrielleId'); 
            $dataCreniMoisProjection = $this->_creniMoisProjectionAdmissionService->findDataMoisProjection($DataCommandeSemestrielleId);
            
            $CreniMoisProjectionAdmission = $entityManager->getRepository(CreniMoisProjectionsAdmissions::class)->find($dataCreniMoisProjection["idCreniMoisProjection"]); 

            if ($isUserHavingDataCreni == 1) {  
                $valueDataCrenasMoisProcetionAdmission = $this->_dataCreniMoisProjectionAdmissionService->findDataCreniMoisProjectionAdmissionByCreniIdAndMoisProjection($DataCreniId, $DataCommandeSemestrielleId);
                $dataCreniMoisProcetionAdmission =  $entityManager->getRepository(DataCreniMoisProjectionAdmission::class)->find($valueDataCrenasMoisProcetionAdmission["id"]);
            } else {
                $dataCreniMoisProcetionAdmission = new DataCreniMoisProjectionAdmission();
                $dataCreniMoisProcetionAdmission->setCreniMoisProjectionsAdmissions($CreniMoisProjectionAdmission);
                $dataCreniMoisProcetionAdmission->setDataCreni($dataCreniEnity);
            }
            

            $dataCreniMoisProcetionAdmission->setDataMois01AdmissionCreniPrecedent($DataMois01AdmissionCreniPrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois02AdmissionCreniPrecedent($DataMois02AdmissionCreniPrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois03AdmissionCreniPrecedent($DataMois03AdmissionCreniPrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois04AdmissionCreniPrecedent($DataMois04AdmissionCreniPrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois05AdmissionCreniPrecedent($DataMois05AdmissionCreniPrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois06AdmissionCreniPrecedent($DataMois06AdmissionCreniPrecedent);

            $dataCreniMoisProcetionAdmission->setDataMois01AdmissionProjeteAnneePrecedent($DataMois01AdmissionProjeteAnneePrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois02AdmissionProjeteAnneePrecedent($DataMois02AdmissionProjeteAnneePrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois03AdmissionProjeteAnneePrecedent($DataMois03AdmissionProjeteAnneePrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois04AdmissionProjeteAnneePrecedent($DataMois04AdmissionProjeteAnneePrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois05AdmissionProjeteAnneePrecedent($DataMois05AdmissionProjeteAnneePrecedent);
            $dataCreniMoisProcetionAdmission->setDataMois06AdmissionProjeteAnneePrecedent($DataMois06AdmissionProjeteAnneePrecedent);

            $dataCreniMoisProcetionAdmission->setDataMois01ProjectionAnneePrevisionnelle($DataMois01ProjectionAnneePrevisionnelle);
            $dataCreniMoisProcetionAdmission->setDataMois02ProjectionAnneePrevisionnelle($DataMois02ProjectionAnneePrevisionnelle);
            $dataCreniMoisProcetionAdmission->setDataMois03ProjectionAnneePrevisionnelle($DataMois03ProjectionAnneePrevisionnelle);
            $dataCreniMoisProcetionAdmission->setDataMois04ProjectionAnneePrevisionnelle($DataMois04ProjectionAnneePrevisionnelle);
            $dataCreniMoisProcetionAdmission->setDataMois05ProjectionAnneePrevisionnelle($DataMois05ProjectionAnneePrevisionnelle);
            $dataCreniMoisProcetionAdmission->setDataMois06ProjectionAnneePrevisionnelle($DataMois06ProjectionAnneePrevisionnelle);

            $entityManager->persist($dataCreniMoisProcetionAdmission);
            $entityManager->flush();
             
            $this->addFlash('success', '<strong>Enregistrement des donnée CRENI avec succès</strong><br/>');
            // Redirect to the homepage or any other route
            return $this->redirectToRoute('app_accueil');
        }

        // Redirect to the root URL (homepage)
        return $this->redirectToRoute('app_accueil'); // Replace 'app_home' with the name of your homepage route
    }

    #[Route('/creni/validation/save', name: 'app_creni_validation_save', methods: ['GET', 'POST'])]
    public function saveValidation(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $ResponsableId = (int) $request->request->get('ResponsableId');
            $DataCreniId = (int) $request->request->get('DataCreniId');
            $DataCommandeSemestrielleId = (int) $request->request->get('DataCommandeSemestrielleId');

            $DataMois01ProjectionEstimatedDistrict = (float) $request->request->get('DataMois01ProjectionEstimatedDistrict');
            $DataMois02ProjectionEstimatedDistrict = (float) $request->request->get('DataMois02ProjectionEstimatedDistrict');
            $DataMois03ProjectionEstimatedDistrict = (float) $request->request->get('DataMois03ProjectionEstimatedDistrict');
            $DataMois04ProjectionEstimatedDistrict = (float) $request->request->get('DataMois04ProjectionEstimatedDistrict');
            $DataMois05ProjectionEstimatedDistrict = (float) $request->request->get('DataMois05ProjectionEstimatedDistrict');
            $DataMois06ProjectionEstimatedDistrict = (float) $request->request->get('DataMois06ProjectionEstimatedDistrict');

            $DataMois01ProjectionEstimatedCentral = (float) $request->request->get('DataMois01ProjectionEstimatedCentral');
            $DataMois02ProjectionEstimatedCentral = (float) $request->request->get('DataMois02ProjectionEstimatedCentral');
            $DataMois03ProjectionEstimatedCentral = (float) $request->request->get('DataMois03ProjectionEstimatedCentral');
            $DataMois04ProjectionEstimatedCentral = (float) $request->request->get('DataMois04ProjectionEstimatedCentral');
            $DataMois05ProjectionEstimatedCentral = (float) $request->request->get('DataMois05ProjectionEstimatedCentral');
            $DataMois06ProjectionEstimatedCentral = (float) $request->request->get('DataMois06ProjectionEstimatedCentral');

            $DataMois01ProjectionValidated = (float) $request->request->get('DataMois01ProjectionValidated');
            $DataMois02ProjectionValidated = (float) $request->request->get('DataMois02ProjectionValidated');
            $DataMois03ProjectionValidated = (float) $request->request->get('DataMois03ProjectionValidated');
            $DataMois04ProjectionValidated = (float) $request->request->get('DataMois04ProjectionValidated');
            $DataMois05ProjectionValidated = (float) $request->request->get('DataMois05ProjectionValidated');
            $DataMois06ProjectionValidated = (float) $request->request->get('DataMois06ProjectionValidated');

            $dataCreniMoisProjection = $this->_creniMoisProjectionAdmissionService->findDataMoisProjection($DataCommandeSemestrielleId);
            
            $CreniMoisProjectionAdmission = $entityManager->getRepository(CreniMoisProjectionsAdmissions::class)->find($dataCreniMoisProjection["idCreniMoisProjection"]); 
             
            $isUserHavingDataValidationCreni= $request->request->get('isUserHavingDataValidationCreni');
            if ($isUserHavingDataValidationCreni == 1) { 
                $valueDataValidationCreni = $this->_dataValidationCreniService->findDataValidationCreniByCreniIdAndMoisProjection($DataCreniId, $DataCommandeSemestrielleId);
                $DataValidationCreni =  $entityManager->getRepository(DataValidationCreni::class)->find($valueDataValidationCreni["id"]);
            } else {
                $DataCreni = $entityManager->getRepository(DataCreni::class)->find($DataCreniId);

                $DataValidationCreni = new DataValidationCreni();
                $DataValidationCreni->setDataCreni($DataCreni);
                $DataValidationCreni->setCreniMoisProjectionsAdmissions($CreniMoisProjectionAdmission);
            }

            $DataValidationCreni->setDataMois01ProjectionEstimatedDistrict($DataMois01ProjectionEstimatedDistrict);
            $DataValidationCreni->setDataMois02ProjectionEstimatedDistrict($DataMois02ProjectionEstimatedDistrict);
            $DataValidationCreni->setDataMois03ProjectionEstimatedDistrict($DataMois03ProjectionEstimatedDistrict);
            $DataValidationCreni->setDataMois04ProjectionEstimatedDistrict($DataMois04ProjectionEstimatedDistrict);
            $DataValidationCreni->setDataMois05ProjectionEstimatedDistrict($DataMois05ProjectionEstimatedDistrict);
            $DataValidationCreni->setDataMois06ProjectionEstimatedDistrict($DataMois06ProjectionEstimatedDistrict);

            $DataValidationCreni->setDataMois01ProjectionEstimatedCentral($DataMois01ProjectionEstimatedCentral);
            $DataValidationCreni->setDataMois02ProjectionEstimatedCentral($DataMois02ProjectionEstimatedCentral);
            $DataValidationCreni->setDataMois03ProjectionEstimatedCentral($DataMois03ProjectionEstimatedCentral);
            $DataValidationCreni->setDataMois04ProjectionEstimatedCentral($DataMois04ProjectionEstimatedCentral);
            $DataValidationCreni->setDataMois05ProjectionEstimatedCentral($DataMois05ProjectionEstimatedCentral);
            $DataValidationCreni->setDataMois06ProjectionEstimatedCentral($DataMois06ProjectionEstimatedCentral);

            $DataValidationCreni->setDataMois01ProjectionValidated($DataMois01ProjectionValidated);
            $DataValidationCreni->setDataMois02ProjectionValidated($DataMois02ProjectionValidated);
            $DataValidationCreni->setDataMois03ProjectionValidated($DataMois03ProjectionValidated);
            $DataValidationCreni->setDataMois04ProjectionValidated($DataMois04ProjectionValidated);
            $DataValidationCreni->setDataMois05ProjectionValidated($DataMois05ProjectionValidated);
            $DataValidationCreni->setDataMois06ProjectionValidated($DataMois06ProjectionValidated);

            $entityManager->persist($DataValidationCreni);
            $entityManager->flush();

            $this->addFlash('success', '<strong>Enregistrement des donnée CRENI avec succès</strong><br/>');
            return $this->redirectToRoute('app_rmanut_extract_historic_responsable_district', ['responsableId' => $ResponsableId]);
        }
        // Redirect to the root URL (homepage)
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/creni/region/{regionId}', name: 'app_creni_region')]
    public function showDataCreniRegion($regionId, EntityManagerInterface $entityManager)
    {
        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
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
            if($arrDataCreniRegion != null && is_array($arrDataCreniRegion) && count($arrDataCreniRegion) > 0) {  
                foreach ($arrDataCreniRegion as $dataCreniRegion) {
                    $lstDataCreniRegion[] = $dataCreniRegion;
                } 
            } 
            
            $dataCreniMoisProjetionAdmission = null;
            if (isset($lstDataCreniRegion) && is_array($lstDataCreniRegion) && count($lstDataCreniRegion) > 0) {
                for ($i=0; $i < count($lstDataCreniRegion); $i++) { 
                    $dataCreniMoisProjetionAdmission[$lstDataCreniRegion[$i]["id"]] = $this->_dataCreniMoisProjectionAdmissionService->findDataCreniMoisProjectionAdmissionByCreniIdAndMoisProjection($lstDataCreniRegion[$i]["id"], $dataCommandeSemestrielle["idCommandeSemestrielle"]);
                }
            }  
          
            
            return $this->render('supervisor/regionCreniCentral.html.twig', [
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
            return $this->redirectToRoute('app_login');
        }
    } 

    #[Route('/export/creni/region/{regionId}', name: 'app_creni_region_export_excel')]
    public function exportExcel($regionId, EntityManagerInterface $entityManager)
    {
        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId(); 
            $dataUser = $this->_userService->findDataUser($userId);  
             
            $Region = $entityManager->getRepository(Region::class)->find($regionId);
            if (!$Region) {
                throw $this->createNotFoundException('La région n\'existe pas.');
            } 
            
            $dataCommandeSemestrielle = $this->_commandeSemestrielleService->findDataCommandeSemestrielle(); 
            $dataMoisProjection = $this->_creniMoisProjectionAdmissionService->findDataMoisProjection($dataCommandeSemestrielle["idCommandeSemestrielle"]);
            
            // Obtenir les informations concernant les données creni enregistrer 
            $lstDataCreniRegion = array();
            $arrDataCreniRegion = $this->_dataCreniService->findDataCreniByRegionId($regionId);
            if($arrDataCreniRegion != null && is_array($arrDataCreniRegion) && count($arrDataCreniRegion) > 0) {  
                foreach ($arrDataCreniRegion as $dataCreniRegion) {
                    $lstDataCreniRegion[] = $dataCreniRegion;
                } 
            } 
            
            $dataCreniMoisProjetionAdmission = null;
            if (isset($lstDataCreniRegion) && is_array($lstDataCreniRegion) && count($lstDataCreniRegion) > 0) {
                for ($i=0; $i < count($lstDataCreniRegion); $i++) { 
                    $dataCreniMoisProjetionAdmission[$lstDataCreniRegion[$i]["id"]] = $this->_dataCreniMoisProjectionAdmissionService->findDataCreniMoisProjectionAdmissionByCreniIdAndMoisProjection($lstDataCreniRegion[$i]["id"], $dataCommandeSemestrielle["idCommandeSemestrielle"]);
                }
            }

            // Créer une nouvelle feuille de calcul
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $nombreMois = 6;

            // --------------------------------- Ligne 1 de désignation -------------------------------------------
            $col = "A"; 

            $row = 2; 
            $rowspan2 = $row + 1; 

            $sheet->mergeCells($col. $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Province"); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1);  
            $sheet->mergeCells($col. $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Région");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col. $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "District");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1);
            $colMerge = $this->shiftColumn($col, $nombreMois);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row); 
            $sheet->setCellValue($col . $row, "Nombre d'admissions CRENI enregistré le semestre précédent");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $colMerge = $this->shiftColumn($col, $nombreMois);
            $sheet->mergeCells($col . $row .':'. $colMerge . $row);
            $sheet->setCellValue($col . $row, "Nombre d'admissions qui avait été projeté le semestre précédent");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2); 
            $sheet->setCellValue($col . $row ,'Résultat'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1);
            $colMerge = $this->shiftColumn($col, $nombreMois);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Projections du nombre d'admissions pour le prochain semestre");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2); 
            $sheet->setCellValue($col . $row ,'Résultat'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'F75 Boites'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'F100 Boites'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'ReSoMal sachet'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'PN sachet'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'Fiche de suivi CRENI unité'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'Fiche de suivi intensif unité'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'Kit médicaments CRENI 10 patients kit'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'Registre CRENI Unité'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row ,'Carnet de rapports mensuels CRENI Unité'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan2);

            $col = $this->shiftColumn($col, 1);
            $colMerge = $this->shiftColumn($col, 18);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row ,'KIT CRENI'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $colMerge = $this->shiftColumn($col, 24);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row ,'Stocks Disponibles et Utilisables (SDU) par produits'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            // --------------------------------- Ligne 2 de désignation -------------------------------------------
            $row = $row + 1;  
            $col = "D";

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois01AdmissionCreniPrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois02AdmissionCreniPrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois03AdmissionCreniPrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois04AdmissionCreniPrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois05AdmissionCreniPrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois06AdmissionCreniPrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Total");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois01AdmissionProjeteAnneePrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois02AdmissionProjeteAnneePrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois03AdmissionProjeteAnneePrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois04AdmissionProjeteAnneePrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois05AdmissionProjeteAnneePrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois06AdmissionProjeteAnneePrecedent"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Total");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 2);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois01ProjectionAnneePrevisionnelle"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois02ProjectionAnneePrevisionnelle"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois03ProjectionAnneePrevisionnelle"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois04ProjectionAnneePrevisionnelle"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois05ProjectionAnneePrevisionnelle"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, $dataMoisProjection["Mois06ProjectionAnneePrevisionnelle"]);            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Total");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 11);
 
            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Amoxici.pdr/oral sus 125mg/5ml/BOT-100ml");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Nystatin oral sus 100,000IU/ml/BOT-30ml");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Fluconazole 50mg caps/PAC-7 (non pas injection)");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Ciprofloxacin 250mg tab/PAC-10");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Ampicillinpdr/inj 500mg vial/BOX-100");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Gentamicininj 40mg/ml 2ml amp/BOX-50");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Sod.lactat.comp.inj 500ml w/g.set/BOX-20");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Glucose inj 5% 500ml w/giv.set/BOX-20");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Glucose hyperton.inj 50% 50mL vl/BOX-25");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Furosemideinj 10mg/ml 2ml amp/BOX-10");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Chlorhexidine conc. sol. 5%/BOT-100ml");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Miconazole nitrate cream 2%/TBE-30g");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Tetracyclineeyeointment 1%/TBE-5g");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Tube,feeding,CH08, L40cm,ster,disp");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Tube,feeding,CH05, L40cm,ster,disp");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Syringe,disp,2ml, w/ndl,21G/BOX-100");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Syringe,disp,10ml, ster/BOX-100");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Syringe,disp,20ml, ster/BOX-100");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Syringe,feeding, 50ml,luer tip,ster");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "F75 Boites");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "F100 Boites");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "ReSoMal sachet");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "PN sachet");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Fiche de suivi CRENI Unité");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Fiche de suivi intensif Unité");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Amoxici.pdr/oral sus 125mg/5ml/BOT-100ml");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Nystatin oral sus 100,000IU/ml/BOT-30ml");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Fluconazole 50mg caps/PAC-7 (non pas injection)");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Ciprofloxacin 250mg tab/PAC-10");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Ampicillinpdr/inj 500mg vial/BOX-100");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Gentamicininj 40mg/ml 2ml amp/BOX-50");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Sod.lactat.comp.inj 500ml w/g.set/BOX-20");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Glucose inj 5% 500ml w/giv.set/BOX-20");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Glucose hyperton.inj 50% 50mL vl/BOX-25");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Furosemideinj 10mg/ml 2ml amp/BOX-10");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Chlorhexidine conc. sol. 5%/BOT-100ml");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Miconazole nitrate cream 2%/TBE-30g");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Tetracyclineeyeointment 1%/TBE-5g");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Tube,feeding,CH08, L40cm,ster,disp");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Tube,feeding,CH05, L40cm,ster,disp");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1); 

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Syringe,disp,2ml, w/ndl,21G/BOX-100");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Syringe,disp,10ml, ster/BOX-100");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Syringe,disp,20ml, ster/BOX-100");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Syringe,feeding,50ml, luer tip,ster");            
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            // --------------------------------- Ligne 4 de Donnée  -------------------------------------------
            $row = $row + 1;
            if (isset($lstDataCreniRegion) && is_array($lstDataCreniRegion) && count($lstDataCreniRegion) > 0) {
                for ($j=0; $j < count($lstDataCreniRegion); $j++) {
                    $col = "A";
                    $dataCreni = $lstDataCreniRegion[$j];
                    $valueDataCreniMoisProjetionAdmission = $dataCreniMoisProjetionAdmission[$dataCreni["id"]];
                    
                    /* ---------- Province - Région - District ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["nomProvince"]);      
                    $this->styleApply($sheet, $col, $row,0,0,1);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["nomRegion"]);     
                    $this->styleApply($sheet, $col, $row,0,0,1); 
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["nomDistrict"]);     
                    $this->styleApply($sheet, $col, $row,0,0,1);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Nombre d'admissions CRENI Semestrielle ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois01AdmissionCreniPrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois02AdmissionCreniPrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois03AdmissionCreniPrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois04AdmissionCreniPrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois05AdmissionCreniPrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois06AdmissionCreniPrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["totalAdmissionCreniSemestrePrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);


                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois01AdmissionProjeteAnneePrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois02AdmissionProjeteAnneePrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois03AdmissionProjeteAnneePrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois04AdmissionProjeteAnneePrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois05AdmissionProjeteAnneePrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois06AdmissionProjeteAnneePrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["totalAdmissionCreniProjetePrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["ResultatDifferenceAdmissionPrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);


                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois01ProjectionAnneePrevisionnelle"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois02ProjectionAnneePrevisionnelle"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois03ProjectionAnneePrevisionnelle"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois04ProjectionAnneePrevisionnelle"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois05ProjectionAnneePrevisionnelle"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $valueDataCreniMoisProjetionAdmission["DataMois06ProjectionAnneePrevisionnelle"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["totalAdmissionCreniProjeterProchain"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["ResultatDifferenceAdmissionProchainPrecedent"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
                    
                    /* ---------- F75 Boites -> Carnet de rapports mensuels CRENI ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["f75Boites"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["f100Boites"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["ReSoMalSachet"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["pnSachet"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["ficheSuiviCreni"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["ficheSuiviIntensif"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitMedicamentsCreni10Patients"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["registreCreni"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["carnetRapportMensuelCreni"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- KIT CRENI ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniAmoxici"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniNystatin"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniFluconazole"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniCiprofloxacin"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniAmpicillinpdr"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniGentamicininj"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniSod"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniGlucoseInj"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniGlucoseHypertonInj"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniFurosemideinj"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniChlorhexidine"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniMiconazole"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniTetracyclineeyeointment"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniTubeFeeding"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniTubeFeedingCH05"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniSyringeDisp2ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniSyringeDisp10ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniSyringeDisp20ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["kitCreniSyringeDisp50ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Stocks Disponibles et Utilisables (SDU) par produits ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduF75Boites"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduF100Boites"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduReSoMal"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduPnSachet"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduFicheSuiviCreni"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduFicheSuiviIntensif"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduAmoxiciPdr"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduNystatinOral"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduFluconazole50mg"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduCiprofloxacin250mg"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduAmpicillinpdrInj500mg"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduGentamicininj40mg"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduSodLactatInj500ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduGlucoseInj500ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduGlucoseHyperton50ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduFurosemideinj10mg"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduChlorhexidineConSol"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduMiconazoleNitrate"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduTetracyclineeyeointment"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduTubeFeedingCH08"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduTubeFeedingCH05"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduSyringeDisp2ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduSyringeDisp10ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduSyringeDisp20ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCreni["sduSyringeFeeding50ml"]);
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $row = $row + 1;
                }
            }


            // Créer un objet Writer pour sauvegarder le fichier Excel
            $writer = new Xlsx($spreadsheet);

            $fileName = 'DATA_CRENI_' . $dataCreni["nomProvince"] . '_' . $dataCreni["nomRegion"] . '_' . date('d-m-Y Hi'). '.xlsx';

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

    public function styleApply($sheet, $col, $row=0, $isFirstRow=0, $isCenterText=1, $isLeftText=0, $colspan=0, $rowspan=0)
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
                $sheet->getStyle($col. $row .':'. $colspan.$rowspan)->applyFromArray($styleFirstRow);
            } else {
                $sheet->getStyle($col. $row)->applyFromArray($styleFirstRow);
            }
            
        }
        if ($isCenterText) {
            if ($colspan != 0 && $rowspan != 0) {
                $sheet->getStyle($col. $row .':'. $colspan.$rowspan)->applyFromArray($styleCenterTextWithBorder);
            } else {
                $sheet->getStyle($col. $row)->applyFromArray($styleCenterTextWithBorder);
            }
        }
        if ($isLeftText) {
            if ($colspan != 0 && $rowspan != 0) {
                $sheet->getStyle($col. $row .':'. $colspan.$rowspan)->applyFromArray($styleLeftTextWithBorder);
            } else {
                $sheet->getStyle($col. $row)->applyFromArray($styleLeftTextWithBorder);
            }
        }
    }
}
