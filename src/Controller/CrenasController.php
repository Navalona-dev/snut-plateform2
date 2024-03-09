<?php

namespace App\Controller;

use App\Entity\DataCrenas;
use App\Entity\DataCrenasMoisProjectionAdmission;
use App\Entity\DataValidationCrenas;
use App\Entity\MoisPrevisionnelleEnclave;
use App\Entity\MoisProjectionsAdmissions;
use App\Finder\AnneePrevisionelleFinder;
use App\Finder\CommandeTrimestrielleFinder;
use App\Finder\DataCrenasFinder;
use App\Finder\DataCrenasMoisProjectionAdmissionFinder;
use App\Finder\DataValidationCrenasFinder;
use App\Finder\GroupeFinder;
use App\Finder\MoisProjectionAdmissionFinder;
use App\Finder\RmaNutFinder;
use App\Finder\UserFinder;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CrenasController extends AbstractController
{
    private $_userService;
    private $_anneePrevisionnelleService;
    private $_commandeTrimestrielleService;
    private $_rmaNutService;
    private $_groupeService;
    private $_moisProjectionAdmissionService;
    private $_dataCrenaService;
    private $_dataCrenasMoisProjectionAdmission;
    private $_dataValidationCrenasService;

    public function __construct(UserFinder $user_service_container, AnneePrevisionelleFinder $annee_previonnelle_container, RmaNutFinder $rma_nut, GroupeFinder $groupe_container, CommandeTrimestrielleFinder $commande_trimestrielle_container, MoisProjectionAdmissionFinder $mois_projection_admission_container, DataCrenasFinder $data_crenas_container, DataCrenasMoisProjectionAdmissionFinder $data_crenas_mois_projection_admission, DataValidationCrenasFinder $data_validation_crenas_container)
    {
        $this->_userService = $user_service_container;   
        $this->_anneePrevisionnelleService = $annee_previonnelle_container;
        $this->_rmaNutService = $rma_nut;
        $this->_groupeService = $groupe_container;
        $this->_commandeTrimestrielleService = $commande_trimestrielle_container;
        $this->_moisProjectionAdmissionService = $mois_projection_admission_container;
        $this->_dataCrenaService = $data_crenas_container;
        $this->_dataCrenasMoisProjectionAdmission = $data_crenas_mois_projection_admission;
        $this->_dataValidationCrenasService = $data_validation_crenas_container;
    }

    #[Route('/crenas', name: 'app_crenas')]
    public function index(Security $security): Response
    {
        
         // Vérifie si un utilisateur est connecté
        $user = $security->getUser(); 
        if ($user) {
            // L'utilisateur est connecté, obtenez son ID
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
            $dataAnneePrevisionnelle = $this->_anneePrevisionnelleService->findDataAnneePrevisionnelle();
            $dataGroupe = $this->_groupeService->findDataGroupe($dataAnneePrevisionnelle["IdAnneePrevisionnelle"], $dataUser["provinceId"], $dataUser['idDistrict']);
            $dataRMANut = $this->_rmaNutService->findDataRmaNutByUserCommandeTrimestrielle($userId, $dataCommandeTrimestrielle['idCommandeTrimestrielle']);
               
            if ($dataRMANut == NULL) {
                return $this->render('crenas/homeCrenas.html.twig', [
                    "dataUser" => $dataUser,
                    "dataRMANut" => $dataRMANut,
                    "dataGroupe" => $dataGroupe
                ]); 
            } else {
                if ($dataGroupe != null) {
                    $dataCrenas = $this->_dataCrenaService->findDataCrenasByUserId($userId, $dataGroupe['idGroupe']);    
                    $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($dataGroupe["idGroupe"], $dataCommandeTrimestrielle['idCommandeTrimestrielle']);
                    $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();

                    $lstMoisAdmissionCRENASAnneePrecedent = array();
                    $lstMoisAdmissionProjeteAnneePrecedent = array();
                    $lstMoisProjectionAnneePrevisionnelle = array();
                
                    if (isset($dataMoisProjection) && count($dataMoisProjection) > 0) {
                        for ($i=0; $i < count($dataMoisProjection); $i++) { 
                            $lstMoisAdmissionCRENASAnneePrecedent[] = $dataMoisProjection[$i]["MoisAdmissionCRENASAnneePrecedent"];
                            $lstMoisAdmissionProjeteAnneePrecedent[] = $dataMoisProjection[$i]["MoisAdmissionProjeteAnneePrecedent"];
                            $lstMoisProjectionAnneePrevisionnelle[] = $dataMoisProjection[$i]["MoisProjectionAnneePrevisionnelle"];
                        }
                    } 
                    // Gestion enclave
                    $isEnclave = false;
                    if ($dataGroupe['type'] != null && $dataGroupe['type'] != "" && $dataGroupe['type'] == "enclave") {
                        $isEnclave = true;
                        $allMoisPrevisionnelle = $this->_moisProjectionAdmissionService->findDataMoisPrevisionnelleProjection($dataGroupe['idGroupe']);
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
                    // Verifier si l'utilisateur a déjà renseigner son donnée CRENAS
                    $isUserHavingDataCrenas = false;
                    $valueDataMoisProjection = null;

                    $lstValueMoisAdmissionCRENASAnneePrecedent = array();
                    $lstValueMoisAdmissionProjeteAnneePrecedent = array();
                    $lstValueMoisProjectionAnneePrevisionnelle = array();

                    if (isset($dataCrenas) && is_array($dataCrenas) && count($dataCrenas) > 0) {
                        $isUserHavingDataCrenas = true;
                        $valueDataMoisProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasId($dataCrenas["id"]);

                        if ($isEnclave) {
                            $valueDataMoisPrevisionneleProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisPrevisionnelleAdmissionByCrenasId($dataCrenas["id"]);
                            $valueDataMoisProjection = array_merge($valueDataMoisProjection, $valueDataMoisPrevisionneleProjection);
                            //dd($valueDataMoisPrevisionneleProjection, $valueDataMoisProjection);
                        }
                        if (isset($valueDataMoisProjection) && count($valueDataMoisProjection) > 0) {
                            for ($i=0; $i < count($valueDataMoisProjection); $i++) {
                                $lstValueMoisAdmissionCRENASAnneePrecedent[] = $valueDataMoisProjection[$i]["DataMoisAdmissionCRENASAnneePrecedent"];
                                $lstValueMoisAdmissionProjeteAnneePrecedent[] = $valueDataMoisProjection[$i]["DataMoisAdmissionProjeteAnneePrecedent"];
                                $lstValueMoisProjectionAnneePrevisionnelle[] = $valueDataMoisProjection[$i]["DataMoisProjectionAnneePrevisionnelle"];
                            }
                        }
                    
                        
                    } 
                
                    $valeurCalculTheoriqueATPE = null;
                    $valeurCalculTheoriqueAMOX = null;
                    $valeurCalculTheoriqueFichePatient = null;
                    $valeurCalculTheoriqueRegistre = null;
                    $valeurCalculTheoriqueCarnetRapport = null; 
                    if (isset($dataCommandeTrimestrielle) && $dataCommandeTrimestrielle != NULL) {
                        if (strpos($dataCommandeTrimestrielle["Slug"], "T1") !== false  && $dataCommandeTrimestrielle["isActive"] == 1) { 
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

                
                    //dump("valeurCalculTheoriqueATPE = " . $valeurCalculTheoriqueATPE . " | valeurCalculTheoriqueAMOX = " . $valeurCalculTheoriqueAMOX . " | valeurCalculTheoriqueFichePatient = " . $valeurCalculTheoriqueFichePatient . " | valeurCalculTheoriqueRegistre = " . $valeurCalculTheoriqueRegistre . " | valeurCalculTheoriqueCarnetRapport = " . $valeurCalculTheoriqueCarnetRapport); dd();

                //dd($lstValueMoisProjectionAnneePrevisionnelle,$dataMoisProjection, $dataGroupe, $lstMoisAdmissionCRENASAnneePrecedent, $dataCrenas);
                    return $this->render('crenas/homeCrenas.html.twig', [
                        "isUserHavingDataCrenas" => $isUserHavingDataCrenas,
                        'controller_name' => 'CrenasController',
                        "dataUser" => $dataUser,
                        "dataRMANut" => $dataRMANut,
                        "dataGroupe" => $dataGroupe,
                        "dataMoisProjection" => $dataMoisProjection,
                        "dataAnneePrevisionnelle" => $dataAnneePrevisionnelle,
                        "dataCommandeTrimestrielle" => $dataCommandeTrimestrielle,
                        "dataCrenas" => $dataCrenas,
                        "lstMoisAdmissionCRENASAnneePrecedent" => $lstMoisAdmissionCRENASAnneePrecedent, 
                        "lstMoisAdmissionProjeteAnneePrecedent" => $lstMoisAdmissionProjeteAnneePrecedent, 
                        "lstMoisProjectionAnneePrevisionnelle" => $lstMoisProjectionAnneePrevisionnelle,
                        "lstValueMoisAdmissionCRENASAnneePrecedent" => $lstValueMoisAdmissionCRENASAnneePrecedent, 
                        "lstValueMoisAdmissionProjeteAnneePrecedent" => $lstValueMoisAdmissionProjeteAnneePrecedent, 
                        "lstValueMoisProjectionAnneePrevisionnelle" => $lstValueMoisProjectionAnneePrevisionnelle,
                        "valeurCalculTheoriqueATPE" => $valeurCalculTheoriqueATPE, 
                        "valeurCalculTheoriqueAMOX" => $valeurCalculTheoriqueAMOX, 
                        "valeurCalculTheoriqueFichePatient" => $valeurCalculTheoriqueFichePatient, 
                        "valeurCalculTheoriqueRegistre" => $valeurCalculTheoriqueRegistre, 
                        "valeurCalculTheoriqueCarnetRapport" => $valeurCalculTheoriqueCarnetRapport
                    ]);
                }
                $dataUser['isEligibleForCrenas'] = false;
                $dataUser['isDistrictIsInGroup'] = false;
                return $this->render('crenas/homeCrenas.html.twig', [
                    "isUserHavingDataCrenas" => false,
                    'controller_name' => 'CrenasController',
                    "dataUser" => $dataUser,
                    "dataRMANut" => $dataRMANut,
                    "dataGroupe" => null,
                    "dataMoisProjection" => [],
                    "dataAnneePrevisionnelle" => $dataAnneePrevisionnelle,
                    "dataCommandeTrimestrielle" => $dataCommandeTrimestrielle,
                    "dataCrenas" => [],
                    "lstMoisAdmissionCRENASAnneePrecedent" => [], 
                    "lstMoisAdmissionProjeteAnneePrecedent" => [], 
                    "lstMoisProjectionAnneePrevisionnelle" => [],
                    "lstValueMoisAdmissionCRENASAnneePrecedent" => [], 
                    "lstValueMoisAdmissionProjeteAnneePrecedent" => [], 
                    "lstValueMoisProjectionAnneePrevisionnelle" => [],
                    "valeurCalculTheoriqueATPE" => [], 
                    "valeurCalculTheoriqueAMOX" => [], 
                    "valeurCalculTheoriqueFichePatient" => [], 
                    "valeurCalculTheoriqueRegistre" => [], 
                    "valeurCalculTheoriqueCarnetRapport" => []
                ]);

            } 
        
        } else { 
            return $this->redirectToRoute('app_login');
        } 
    }

    #[Route('/crenas/save', name: 'app_crenas_save', methods: ['GET', 'POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager, Security $security, GroupeRepository $groupeRepository): Response
    {
        if ($request->isMethod('POST')) {  
            $totalCRENASAnneePrecedent = (float) $request->request->get('totalCRENASAnneePrecedent');
            $totalProjeteAnneePrecedent = (float) $request->request->get('totalProjeteAnneePrecedent');
            $totalAnneeProjection = (float) $request->request->get('totalAnneeProjection');
            $resultatAnneePrecedent = (float) $request->request->get('resultatAnneePrecedent');
            $resultatAnneeProjection = (float) $request->request->get('resultatAnneeProjection'); 
         
            // DEBUT GET QUANTITE NECESSAIRE EN INTRANTS NUTRITIONS BESOINS THEORIQUES
            $besoinAPTE = (float) $request->request->get('besoinAPTE');
            $besoinAMOX = (float) $request->request->get('besoinAMOX');
            $besoinFichePatient = (float) $request->request->get('besoinFichePatient');
            $besoinRegistre = (float) $request->request->get('besoinRegistre');
            $besoinCarnetRapportCRENAS = (float) $request->request->get('besoinCarnetRapportCRENAS');
            // FIN GET QUANTITE NECESSAIRE EN INTRANTS NUTRITIONS BESOINS THEORIQUES
            
            // DEBUT GET DATA AMOX 

            $quantite01AmoxSDUCartonBSD = (float) $request->request->get('quantite01AmoxSDUCartonBSD');
            $quantite02AmoxSDUCartonBSD = (float) $request->request->get('quantite02AmoxSDUCartonBSD');
            $quantite03AmoxSDUCartonBSD = (float) $request->request->get('quantite03AmoxSDUCartonBSD');
            $quantite04AmoxSDUCartonBSD = (float) $request->request->get('quantite04AmoxSDUCartonBSD');

            $dateExpiration01AmoxSDUCartonBSD = $request->request->get('dateExpiration01AmoxSDUCartonBSD');
            $dateExpiration02AmoxSDUCartonBSD = $request->request->get('dateExpiration02AmoxSDUCartonBSD');
            $dateExpiration03AmoxSDUCartonBSD = $request->request->get('dateExpiration03AmoxSDUCartonBSD');
            $dateExpiration04AmoxSDUCartonBSD = $request->request->get('dateExpiration04AmoxSDUCartonBSD');

            $quantite01AmoxSDUCartonCSB = (float) $request->request->get('quantite01AmoxSDUCartonCSB');
            $quantite02AmoxSDUCartonCSB = (float) $request->request->get('quantite02AmoxSDUCartonCSB');
            $quantite03AmoxSDUCartonCSB = (float) $request->request->get('quantite03AmoxSDUCartonCSB');
            $quantite04AmoxSDUCartonCSB = (float) $request->request->get('quantite04AmoxSDUCartonCSB');

            $dateExpiration01AmoxSDUCartonCSB = $request->request->get('dateExpiration01AmoxSDUCartonCSB');
            $dateExpiration02AmoxSDUCartonCSB = $request->request->get('dateExpiration02AmoxSDUCartonCSB');
            $dateExpiration03AmoxSDUCartonCSB = $request->request->get('dateExpiration03AmoxSDUCartonCSB');
            $dateExpiration04AmoxSDUCartonCSB = $request->request->get('dateExpiration04AmoxSDUCartonCSB');

            $totalAmoxSDUCartonBSD = (float) $request->request->get('totalAmoxSDUCartonBSD');
            $totalAmoxSDUCartonCSB = (float) $request->request->get('totalAmoxSDUCartonCSB');
            $totalAmoxSDUCartonSDSP = (float) $request->request->get('totalAmoxSDUCartonSDSP');

            // FIN GET DATA AMOX 

            // DEBUT GET DATA PN 

            $quantite01PnSDUCartonBSD = (float) $request->request->get('quantite01PnSDUCartonBSD');
            $quantite02PnSDUCartonBSD = (float) $request->request->get('quantite02PnSDUCartonBSD');
            $quantite03PnSDUCartonBSD = (float) $request->request->get('quantite03PnSDUCartonBSD');
            $quantite04PnSDUCartonBSD = (float) $request->request->get('quantite04PnSDUCartonBSD');

            $dateExpiration01PnSDUCartonBSD = $request->request->get('dateExpiration01PnSDUCartonBSD');
            $dateExpiration02PnSDUCartonBSD = $request->request->get('dateExpiration02PnSDUCartonBSD');
            $dateExpiration03PnSDUCartonBSD = $request->request->get('dateExpiration03PnSDUCartonBSD');
            $dateExpiration04PnSDUCartonBSD = $request->request->get('dateExpiration04PnSDUCartonBSD');

            $quantite01PnSDUCartonCSB = (float) $request->request->get('quantite01PnSDUCartonCSB');
            $quantite02PnSDUCartonCSB = (float) $request->request->get('quantite02PnSDUCartonCSB');
            $quantite03PnSDUCartonCSB = (float) $request->request->get('quantite03PnSDUCartonCSB');
            $quantite04PnSDUCartonCSB = (float) $request->request->get('quantite04PnSDUCartonCSB');

            $dateExpiration01PnSDUCartonCSB = $request->request->get('dateExpiration01PnSDUCartonCSB');
            $dateExpiration02PnSDUCartonCSB = $request->request->get('dateExpiration02PnSDUCartonCSB');
            $dateExpiration03PnSDUCartonCSB = $request->request->get('dateExpiration03PnSDUCartonCSB');
            $dateExpiration04PnSDUCartonCSB = $request->request->get('dateExpiration04PnSDUCartonCSB');

            $totalPnSDUCartonBSD = (float) $request->request->get('totalPnSDUCartonBSD');
            $totalPnSDUCartonCSB = (float) $request->request->get('totalPnSDUCartonCSB');
            $totalPnSDUCartonSDSP = (float) $request->request->get('totalPnSDUCartonSDSP');

            // FIN GET DATA PN 
            $sduFiche = (float) $request->request->get('sduFiche');
            $nbrCSBCRENAS = (float) $request->request->get('nbrCSBCRENAS');
            $nbrTotalCsb = (float) $request->request->get('nbrTotalCsb');
            $tauxCouvertureCRENAS = (float) $request->request->get('tauxCouvertureCRENAS');
            $nbrCSBCRENASCommande = (float) $request->request->get('nbrCSBCRENASCommande');
            $tauxEnvoiCommandeCSBCRENAS = (float) $request->request->get('tauxEnvoiCommandeCSBCRENAS');
             
            $isUserHavingDataCrenas = $request->request->get('isUserHavingDataCrenas');
            if ($isUserHavingDataCrenas == 1) { // Edit an entity
                $DataCrenasId =  $request->request->get('DataCrenasId');
                $dataCrenasEnity = $entityManager->getRepository(DataCrenas::class)->find($DataCrenasId); 
            } else { // Create an entity 
                $dataCrenasEnity = new DataCrenas();
                $user = $this->getUser();
                $dataCrenasEnity->setUser($user);
            }

            $GroupeId = (int) $request->request->get('GroupeId');
            $groupeEntity = $groupeRepository->find($GroupeId);
            if ($groupeEntity) {
                $dataCrenasEnity->setGroupe($groupeEntity);
            }
            
            $dataCrenasEnity->setTotalCRENASAnneePrecedent($totalCRENASAnneePrecedent);
            $dataCrenasEnity->setTotalProjeteAnneePrecedent($totalProjeteAnneePrecedent);
            $dataCrenasEnity->setTotalAnneeProjection($totalAnneeProjection);
            $dataCrenasEnity->setResultatAnneeProjection($resultatAnneeProjection);
            $dataCrenasEnity->setResultatAnneePrecedent($resultatAnneePrecedent);

            $dataCrenasEnity->setBesoinAPTE($besoinAPTE); 
            $dataCrenasEnity->setBesoinAMOX($besoinAMOX);
            $dataCrenasEnity->setBesoinFichePatient($besoinFichePatient);
            $dataCrenasEnity->setBesoinRegistre($besoinRegistre);
            $dataCrenasEnity->setBesoinCarnetRapportCRENAS($besoinCarnetRapportCRENAS); 

            // DEBUT SET DATA AMOX 

            $dataCrenasEnity->setQuantite01AmoxSDUCartonBSD($quantite01AmoxSDUCartonBSD);
            $dataCrenasEnity->setQuantite02AmoxSDUCartonBSD($quantite02AmoxSDUCartonBSD);
            $dataCrenasEnity->setQuantite03AmoxSDUCartonBSD($quantite03AmoxSDUCartonBSD);
            $dataCrenasEnity->setQuantite04AmoxSDUCartonBSD($quantite04AmoxSDUCartonBSD);

            $dataCrenasEnity->setDateExpiration01AmoxSDUCartonBSD($dateExpiration01AmoxSDUCartonBSD);
            $dataCrenasEnity->setDateExpiration02AmoxSDUCartonBSD($dateExpiration02AmoxSDUCartonBSD);
            $dataCrenasEnity->setDateExpiration03AmoxSDUCartonBSD($dateExpiration03AmoxSDUCartonBSD);
            $dataCrenasEnity->setDateExpiration04AmoxSDUCartonBSD($dateExpiration04AmoxSDUCartonBSD); 

            $dataCrenasEnity->setQuantite01AmoxSDUCartonCSB($quantite01AmoxSDUCartonCSB);
            $dataCrenasEnity->setQuantite02AmoxSDUCartonCSB($quantite02AmoxSDUCartonCSB);
            $dataCrenasEnity->setQuantite03AmoxSDUCartonCSB($quantite03AmoxSDUCartonCSB);
            $dataCrenasEnity->setQuantite04AmoxSDUCartonCSB($quantite04AmoxSDUCartonCSB);

            $dataCrenasEnity->setDateExpiration01AmoxSDUCartonCSB($dateExpiration01AmoxSDUCartonCSB);
            $dataCrenasEnity->setDateExpiration02AmoxSDUCartonCSB($dateExpiration02AmoxSDUCartonCSB);
            $dataCrenasEnity->setDateExpiration03AmoxSDUCartonCSB($dateExpiration03AmoxSDUCartonCSB);
            $dataCrenasEnity->setDateExpiration04AmoxSDUCartonCSB($dateExpiration04AmoxSDUCartonCSB);

            $dataCrenasEnity->setTotalAmoxSDUCartonBSD($totalAmoxSDUCartonBSD);
            $dataCrenasEnity->setTotalAmoxSDUCartonCSB($totalAmoxSDUCartonCSB);
            $dataCrenasEnity->setTotalAmoxSDUCartonSDSP($totalAmoxSDUCartonSDSP);

            // FIN SET DATA AMOX 

            // DEBUT SET DATA PN

            $dataCrenasEnity->setQuantite01PnSDUCartonBSD($quantite01PnSDUCartonBSD);
            $dataCrenasEnity->setQuantite02PnSDUCartonBSD($quantite02PnSDUCartonBSD);
            $dataCrenasEnity->setQuantite03PnSDUCartonBSD($quantite03PnSDUCartonBSD);
            $dataCrenasEnity->setQuantite04PnSDUCartonBSD($quantite04PnSDUCartonBSD);

            $dataCrenasEnity->setDateExpiration01PnSDUCartonBSD($dateExpiration01PnSDUCartonBSD);
            $dataCrenasEnity->setDateExpiration02PnSDUCartonBSD($dateExpiration02PnSDUCartonBSD);
            $dataCrenasEnity->setDateExpiration03PnSDUCartonBSD($dateExpiration03PnSDUCartonBSD);
            $dataCrenasEnity->setDateExpiration04PnSDUCartonBSD($dateExpiration04PnSDUCartonBSD);

            $dataCrenasEnity->setQuantite01PnSDUCartonCSB($quantite01PnSDUCartonCSB);
            $dataCrenasEnity->setQuantite02PnSDUCartonCSB($quantite02PnSDUCartonCSB);
            $dataCrenasEnity->setQuantite03PnSDUCartonCSB($quantite03PnSDUCartonCSB);
            $dataCrenasEnity->setQuantite04PnSDUCartonCSB($quantite04PnSDUCartonCSB);

            $dataCrenasEnity->setDateExpiration01PnSDUCartonCSB($dateExpiration01PnSDUCartonCSB);
            $dataCrenasEnity->setDateExpiration02PnSDUCartonCSB($dateExpiration02PnSDUCartonCSB);
            $dataCrenasEnity->setDateExpiration03PnSDUCartonCSB($dateExpiration03PnSDUCartonCSB);
            $dataCrenasEnity->setDateExpiration04PnSDUCartonCSB($dateExpiration04PnSDUCartonCSB);

            $dataCrenasEnity->setTotalPnSDUCartonBSD($totalPnSDUCartonBSD);
            $dataCrenasEnity->setTotalPnSDUCartonCSB($totalPnSDUCartonCSB);
            $dataCrenasEnity->setTotalPnSDUCartonSDSP($totalPnSDUCartonSDSP);

            // FIN SET DATA PN

            $dataCrenasEnity->setSduFiche($sduFiche);
            $dataCrenasEnity->setNbrCSBCRENAS($nbrCSBCRENAS);
            $dataCrenasEnity->setNbrTotalCsb($nbrTotalCsb);
            $dataCrenasEnity->setTauxCouvertureCRENAS($tauxCouvertureCRENAS);
            $dataCrenasEnity->setNbrCSBCRENASCommande($nbrCSBCRENASCommande);
            $dataCrenasEnity->setTauxEnvoiCommandeCSBCRENAS($tauxEnvoiCommandeCSBCRENAS);

            // Persist data to the database using Doctrine
            //dump($dataCrenasEnity);dd();
            $entityManager->persist($dataCrenasEnity);
            $entityManager->flush();

            $GroupeId = (int) $request->request->get('GroupeId');
            $DataCommandeTrimestrielleId = (int) $request->request->get('DataCommandeTrimestrielleId');

            $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($GroupeId, $DataCommandeTrimestrielleId);
            

            // Gestion enclave
            $user = $security->getUser();
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataAnneePrevisionnelle = $this->_anneePrevisionnelleService->findDataAnneePrevisionnelle();
            $dataGroupe = $this->_groupeService->findDataGroupe($dataAnneePrevisionnelle["IdAnneePrevisionnelle"], $dataUser["provinceId"], $dataUser['idDistrict']);
            if ($dataGroupe['type'] != null && $dataGroupe['type'] != "" && $dataGroupe['type'] == "enclave") {
                $allMoisPrevisionnelle = $this->_moisProjectionAdmissionService->findDataMoisPrevisionnelleProjection($dataGroupe['idGroupe']);
                $tabMoisProjection = [];
                
                if (count($dataMoisProjection) > 0) {
                    foreach($dataMoisProjection as $moisProjection) {
                        $monthProj = [];
                        $monthProj['isPrevisionnel'] = false;
                        $monthProj['idMoisProjection'] = $moisProjection['idMoisProjection'];
                        $monthProj['MoisAdmissionCRENASAnneePrecedent'] = $moisProjection['MoisAdmissionCRENASAnneePrecedent'];
                        $monthProj['MoisAdmissionProjeteAnneePrecedent'] = $moisProjection['MoisAdmissionProjeteAnneePrecedent'];
                        $monthProj['MoisProjectionAnneePrevisionnelle'] = $moisProjection['MoisProjectionAnneePrevisionnelle'];
                        array_push($tabMoisProjection, $monthProj);
                    }
                }
                if (count($allMoisPrevisionnelle) > 0) {
                    foreach($allMoisPrevisionnelle as $moisPrevision) {
                             $monthPrev = [];
                             $monthPrev['isPrevisionnel'] = true;
                             $monthPrev['idMoisProjection'] = $moisPrevision['idMoisProjection'];
                             $monthPrev['MoisAdmissionCRENASAnneePrecedent'] = null;
                             $monthPrev['MoisAdmissionProjeteAnneePrecedent'] = null;
                             $monthPrev['MoisProjectionAnneePrevisionnelle'] = $moisPrevision['MoisProjectionAnneePrevisionnelle'];
                            array_push($tabMoisProjection, $monthPrev);
                       
                    }
                }
              // dd($request->request);
                if(isset($tabMoisProjection) && is_array($tabMoisProjection) && count($tabMoisProjection) > 0) {
                    for ($i=0; $i < count($tabMoisProjection); $i++) {
                        $idMoisDeProjection = $tabMoisProjection[$i]["idMoisProjection"];
                        $isPrevisionnel = $tabMoisProjection[$i]["isPrevisionnel"];
                        $incr = $i+1;
    
                        $dataMoisAdmissionCRENASAnneePrecedent = (float) $request->request->get('moisAdmissionCRENASAnneePrecedent'.$incr);
                        $dataMoisAdmissionProjeteAnneePrecedent = (float) $request->request->get('moisAdmissionProjeteAnneePrecedent'.$incr);
                        $dataMoisProjectionAnneePrevisionnelle = (float) $request->request->get('moisProjectionAnneePrevisionnelle'.$incr);
    
                        if ($isUserHavingDataCrenas == 1) { // Edit an entity
                            // Prendre data à partir de l'ID dataCrenasId et l'ID moisProjectionsAdmissions
                            if (!$isPrevisionnel) {
                                $valueDataCrenasMoisProcetionAdmission = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasIdAndMoisProjection($DataCrenasId, $idMoisDeProjection);
                                $dataCrenasMoisProcetionAdmission =  $entityManager->getRepository(DataCrenasMoisProjectionAdmission::class)->find($valueDataCrenasMoisProcetionAdmission["id"]);
                            } else {
                                $valueDataCrenasMoisProcetionAdmission = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisPrevisionnelleAdmissionByCrenasIdAndMoisProjection($DataCrenasId, $idMoisDeProjection);
                                $dataCrenasMoisProcetionAdmission =  $entityManager->getRepository(DataCrenasMoisProjectionAdmission::class)->find($valueDataCrenasMoisProcetionAdmission["id"]);
                            }
                            
                        } else {
                            if (!$isPrevisionnel) {
                                $MoisProjectionAdmission = $entityManager->getRepository(MoisProjectionsAdmissions::class)->find($idMoisDeProjection); 
                                $dataCrenasMoisProcetionAdmission = new DataCrenasMoisProjectionAdmission();
                                $dataCrenasMoisProcetionAdmission->setMoisProjectionsAdmissions($MoisProjectionAdmission);
                            } else {
                                $MoisPrevisionneleEnclave = $entityManager->getRepository(MoisPrevisionnelleEnclave::class)->find($idMoisDeProjection); 
                                $dataCrenasMoisProcetionAdmission = new DataCrenasMoisProjectionAdmission();
                                $dataCrenasMoisProcetionAdmission->setMoisPrevisionnelleEnclave($MoisPrevisionneleEnclave);
                            }
                            
                        } 
                        
                        $dataCrenasMoisProcetionAdmission->setDataCrenas($dataCrenasEnity);
                        $dataCrenasMoisProcetionAdmission->setDataMoisAdmissionCRENASAnneePrecedent($dataMoisAdmissionCRENASAnneePrecedent);
                        $dataCrenasMoisProcetionAdmission->setDataMoisAdmissionProjeteAnneePrecedent($dataMoisAdmissionProjeteAnneePrecedent);
                        $dataCrenasMoisProcetionAdmission->setDataMoisProjectionAnneePrevisionnelle($dataMoisProjectionAnneePrevisionnelle);
                        
                        $entityManager->persist($dataCrenasMoisProcetionAdmission);
                        $entityManager->flush();
    
                    }
                }
             
               // dd($dataGroupe, $lstMoisProjectionAnneePrevisionnelle, $allMoisPrevisionnelle);
            } else {
                if(isset($dataMoisProjection) && is_array($dataMoisProjection) && count($dataMoisProjection) > 0) {
                    for ($i=0; $i < count($dataMoisProjection); $i++) {
                        $idMoisDeProjection = $dataMoisProjection[$i]["idMoisProjection"];
    
                        $incr = $i+1;
    
                        $dataMoisAdmissionCRENASAnneePrecedent = (float) $request->request->get('moisAdmissionCRENASAnneePrecedent'.$incr);
                        $dataMoisAdmissionProjeteAnneePrecedent = (float) $request->request->get('moisAdmissionProjeteAnneePrecedent'.$incr);
                        $dataMoisProjectionAnneePrevisionnelle = (float) $request->request->get('moisProjectionAnneePrevisionnelle'.$incr);
    
                        if ($isUserHavingDataCrenas == 1) { // Edit an entity
                            // Prendre data à partir de l'ID dataCrenasId et l'ID moisProjectionsAdmissions
                            $valueDataCrenasMoisProcetionAdmission = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasIdAndMoisProjection($DataCrenasId, $idMoisDeProjection);
                            $dataCrenasMoisProcetionAdmission =  $entityManager->getRepository(DataCrenasMoisProjectionAdmission::class)->find($valueDataCrenasMoisProcetionAdmission["id"]);
                        } else {
                            $MoisProjectionAdmission = $entityManager->getRepository(MoisProjectionsAdmissions::class)->find($idMoisDeProjection); 
                            $dataCrenasMoisProcetionAdmission = new DataCrenasMoisProjectionAdmission();
                            $dataCrenasMoisProcetionAdmission->setMoisProjectionsAdmissions($MoisProjectionAdmission);
                        } 
                        
                        $dataCrenasMoisProcetionAdmission->setDataCrenas($dataCrenasEnity);
                        $dataCrenasMoisProcetionAdmission->setDataMoisAdmissionCRENASAnneePrecedent($dataMoisAdmissionCRENASAnneePrecedent);
                        $dataCrenasMoisProcetionAdmission->setDataMoisAdmissionProjeteAnneePrecedent($dataMoisAdmissionProjeteAnneePrecedent);
                        $dataCrenasMoisProcetionAdmission->setDataMoisProjectionAnneePrevisionnelle($dataMoisProjectionAnneePrevisionnelle);
                        
                        $entityManager->persist($dataCrenasMoisProcetionAdmission);
                        $entityManager->flush();
    
                    }
                }
            }
           
            // Gestion enclave
            
            $this->addFlash('success', '<strong>Enregistrement des donnée CRENAS avec succès</strong><br/>');
            // Redirect to the homepage or any other route
            return $this->redirectToRoute('app_accueil');
        }
        // Redirect to the root URL (homepage)
        return $this->redirectToRoute('app_accueil'); // Replace 'app_home' with the name of your homepage route
    }

    #[Route('/crenas/validation/save', name: 'app_crenas_validation_save', methods: ['GET', 'POST'])]
    public function saveValidation(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $GroupeId = (int) $request->request->get('GroupeId');
            $DataCommandeTrimestrielleId = (int) $request->request->get('DataCommandeTrimestrielleId');
            $ResponsableId = (int) $request->request->get('ResponsableId');
            $DataCrenasId = (int) $request->request->get('DataCrenasId');
            $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($GroupeId, $DataCommandeTrimestrielleId);
            if(isset($dataMoisProjection) && is_array($dataMoisProjection) && count($dataMoisProjection) > 0) {
                for ($i=0; $i < count($dataMoisProjection); $i++) {
                    $idMoisDeProjection = $dataMoisProjection[$i]["idMoisProjection"]; 

                    $incr = $i+1;

                    $EstimatedDataMonthDistrict = (float) $request->request->get('EstimatedDataMonthDistrict'.$incr);
                    $EstimatedDataMonthCentral = (float) $request->request->get('EstimatedDataMonthCentral'.$incr);
                    $ValidatedDataMonth = (float) $request->request->get('ValidatedDataMonth'.$incr); 

                    $MoisProjectionAdmission = $entityManager->getRepository(MoisProjectionsAdmissions::class)->find($idMoisDeProjection);
                    $DataCrenas = $entityManager->getRepository(DataCrenas::class)->find($DataCrenasId); 

                    $isUserHavingDataValidationCrenas = $request->request->get('isUserHavingDataValidationCrenas');
                    if ($isUserHavingDataValidationCrenas == 1) { 
                        $valueDataValidationCrenas = $this->_dataValidationCrenasService->findDataValidationCrenasByCrenasIdAndMoisProjection($DataCrenasId, $idMoisDeProjection);
                        $DataValidationCrenas =  $entityManager->getRepository(DataValidationCrenas::class)->find($valueDataValidationCrenas["id"]);
                    } else {
                        $DataValidationCrenas = new DataValidationCrenas();
                        $DataValidationCrenas->setDataCrenas($DataCrenas);
                        $DataValidationCrenas->setMoisProjectionsAdmissions($MoisProjectionAdmission);
                    }
                    
                    $DataValidationCrenas->setEstimatedDataMonthDistrict($EstimatedDataMonthDistrict);
                    $DataValidationCrenas->setEstimatedDataMonthCentral($EstimatedDataMonthCentral);
                    $DataValidationCrenas->setValidatedDataMonth($ValidatedDataMonth);

                    $entityManager->persist($DataValidationCrenas);
                    $entityManager->flush();
                }
                $this->addFlash('success', '<strong>Enregistrement des donnée CRENAS avec succès</strong><br/>');
                return $this->redirectToRoute('app_rmanut_extract_historic_responsable_district', ['responsableId' => $ResponsableId]);
            }
             
        }
        // Redirect to the root URL (homepage)
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/crenas/group/{groupId}', name: 'app_crenas_group')]
    public function showDataGroup($groupId, EntityManagerInterface $entityManager)
    {
         // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
         $user = $this->getUser();  
         if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataGroupe = $this->_groupeService->findById($groupId); 

            // Obtenir les informations concernant les données crenas enregistrer
            $lstDataCrenasGroupe = array();
            $arrDataCrenasGroupe =  $this->_dataCrenaService->findDataCrenasByGroupe($groupId); 
          
            if($arrDataCrenasGroupe != null && is_array($arrDataCrenasGroupe) && count($arrDataCrenasGroupe) > 0) {  
                foreach ($arrDataCrenasGroupe as $dataCrenasGroupe) {
                    if (isset($dataCrenasGroupe) && is_array($dataCrenasGroupe) && count($dataCrenasGroupe)>0) {
                        for ($i=0; $i < count($dataCrenasGroupe); $i++) {
                            $lstDataCrenasGroupe[] = $dataCrenasGroupe[$i];
                        }
                    }
                } 
            }  
             
            $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
            $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($groupId, $dataCommandeTrimestrielle['idCommandeTrimestrielle']);
 
            $lstMoisAdmissionCRENASAnneePrecedent = array();
            $lstMoisAdmissionProjeteAnneePrecedent = array();
            $lstMoisProjectionAnneePrevisionnelle = array();

            if (isset($dataMoisProjection) && count($dataMoisProjection) > 0) {
                for ($i=0; $i < count($dataMoisProjection); $i++) { 
                    $lstMoisAdmissionCRENASAnneePrecedent[] = $dataMoisProjection[$i]["MoisAdmissionCRENASAnneePrecedent"];
                    $lstMoisAdmissionProjeteAnneePrecedent[] = $dataMoisProjection[$i]["MoisAdmissionProjeteAnneePrecedent"];
                    $lstMoisProjectionAnneePrevisionnelle[] = $dataMoisProjection[$i]["MoisProjectionAnneePrevisionnelle"];
                }
            }

            $lstValueMoisAdmissionCRENASAnneePrecedent = array();
            $lstValueMoisAdmissionProjeteAnneePrecedent = array();
            $lstValueMoisProjectionAnneePrevisionnelle = array(); 
            
            if (isset($lstDataCrenasGroupe) && is_array($lstDataCrenasGroupe) && count($lstDataCrenasGroupe) > 0) {
                for ($j=0; $j < count($lstDataCrenasGroupe); $j++) {
                    $dataCrenasId = $lstDataCrenasGroupe[$j]["id"]; 
                    $valueDataMoisProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasId($dataCrenasId);

                    if (isset($valueDataMoisProjection) && count($valueDataMoisProjection) > 0) {
                        for ($i=0; $i < count($valueDataMoisProjection); $i++) {
                            $lstValueMoisAdmissionCRENASAnneePrecedent[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisAdmissionCRENASAnneePrecedent"];
                            $lstValueMoisAdmissionProjeteAnneePrecedent[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisAdmissionProjeteAnneePrecedent"];
                            $lstValueMoisProjectionAnneePrevisionnelle[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisProjectionAnneePrevisionnelle"];
                        }
                    }
                }
            }

            return $this->render('supervisor/crenasGroupe.html.twig', [
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

    #[Route('/export/crenas/group/{groupId}', name: 'app_crenas_group_export_excel')]
    public function exportExcel($groupId)
    {
         
        // Récupérez l'utilisateur actuellement connecté (vous pouvez utiliser le système de sécurité de Symfony pour cela).
        $user = $this->getUser();
        if ($user) {  
            
            
            $dataGroupe = $this->_groupeService->findById($groupId); 

            // Obtenir les informations concernant les données crenas enregistrer
            $lstDataCrenasGroupe = array();
            $arrDataCrenasGroupe =  $this->_dataCrenaService->findDataCrenasByGroupe($groupId); 
            if($arrDataCrenasGroupe != null && is_array($arrDataCrenasGroupe) && count($arrDataCrenasGroupe) > 0) {  
                foreach ($arrDataCrenasGroupe as $dataCrenasGroupe) {
                    if (isset($dataCrenasGroupe) && is_array($dataCrenasGroupe) && count($dataCrenasGroupe)>0) {
                        for ($i=0; $i < count($dataCrenasGroupe); $i++) {
                            $lstDataCrenasGroupe[] = $dataCrenasGroupe[$i];
                        }
                    }
                } 
            }  
            
            $dataCommandeTrimestrielle = $this->_commandeTrimestrielleService->findDataCommandeTrimestrielle();
            $dataMoisProjection = $this->_moisProjectionAdmissionService->findDataMoisProjection($groupId, $dataCommandeTrimestrielle['idCommandeTrimestrielle']);

            $lstMoisAdmissionCRENASAnneePrecedent = array();
            $lstMoisAdmissionProjeteAnneePrecedent = array();
            $lstMoisProjectionAnneePrevisionnelle = array();

            if (isset($dataMoisProjection) && count($dataMoisProjection) > 0) {
                for ($i=0; $i < count($dataMoisProjection); $i++) { 
                    $lstMoisAdmissionCRENASAnneePrecedent[] = $dataMoisProjection[$i]["MoisAdmissionCRENASAnneePrecedent"];
                    $lstMoisAdmissionProjeteAnneePrecedent[] = $dataMoisProjection[$i]["MoisAdmissionProjeteAnneePrecedent"];
                    $lstMoisProjectionAnneePrevisionnelle[] = $dataMoisProjection[$i]["MoisProjectionAnneePrevisionnelle"];
                }
            }

            $lstValueMoisAdmissionCRENASAnneePrecedent = array();
            $lstValueMoisAdmissionProjeteAnneePrecedent = array();
            $lstValueMoisProjectionAnneePrevisionnelle = array(); 
            
            if (isset($lstDataCrenasGroupe) && is_array($lstDataCrenasGroupe) && count($lstDataCrenasGroupe) > 0) {
                for ($j=0; $j < count($lstDataCrenasGroupe); $j++) {
                    $dataCrenasId = $lstDataCrenasGroupe[$j]["id"]; 
                    $valueDataMoisProjection = $this->_dataCrenasMoisProjectionAdmission->findDataCrenasMoisProjectionAdmissionByCrenasId($dataCrenasId);

                    if (isset($valueDataMoisProjection) && count($valueDataMoisProjection) > 0) {
                        for ($i=0; $i < count($valueDataMoisProjection); $i++) {
                            $lstValueMoisAdmissionCRENASAnneePrecedent[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisAdmissionCRENASAnneePrecedent"];
                            $lstValueMoisAdmissionProjeteAnneePrecedent[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisAdmissionProjeteAnneePrecedent"];
                            $lstValueMoisProjectionAnneePrevisionnelle[$dataCrenasId][] = $valueDataMoisProjection[$i]["DataMoisProjectionAnneePrevisionnelle"];
                        }
                    }
                }
            }   

            // Créer une nouvelle feuille de calcul
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet(); 

            $nombreMois = count($dataMoisProjection);
            $nombreMoisWithTotal = $nombreMois + 1;

            

            // --------------------------------- Ligne 1 de désignation -------------------------------------------
            $col = "A"; 

            $row = 2;
            $rowspan3 = $row + 2;
            $rowspan2 = $row + 1; 

            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "Province"); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);  
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "Région");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "District");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);
            $colMerge = $this->shiftColumn($col, $nombreMois);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row); 
            $sheet->setCellValue($col . $row, "Nombre d'admissions CRENAS enregistré");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $colMerge = $this->shiftColumn($col, $nombreMois);
            $sheet->mergeCells($col . $row .':'. $colMerge . $row);
            $sheet->setCellValue($col . $row, "Nombre d'admissions qui avait été projeté");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $sheet->mergeCells($col . $row .':' . $col . $rowspan3); 
            $sheet->setCellValue($col . $row ,'Résultat'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);
            $colMerge = $this->shiftColumn($col, $nombreMois);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Projections du nombre d'admissions");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $sheet->mergeCells($col . $row .':' . $col . $rowspan3); 
            $sheet->setCellValue($col . $row ,'Résultat'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($colMerge, 2);
            $colMerge = $this->shiftColumn($col, 4);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row); 
            $sheet->setCellValue($col . $row ,'Quantité nécessaire en intrants nutrition (Besoins théoriques)'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $colMerge = $this->shiftColumn($col, 8);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row); 
            $sheet->setCellValue($col . $row ,'Stock Disponible et Utilisable à l’inventaire AMOX en Boite de 100 cp (SDU total BSD) (SDU AMOX Boite de 100 cp au niveau BSD)'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);
    
            $col = $this->shiftColumn($colMerge, 1);
            $colMerge = $this->shiftColumn($col, 8);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row); 
            $sheet->setCellValue($col . $row ,'Stock Disponible et Utilisable AMOX à l’inventaire en Boite de 100 cp (SDU total CSB) (SDU AMOX Boite de 100 cp au niveau CSB)');
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $sheet->mergeCells($col . $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "TOTAL SDU PN en carton SDSP");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($colMerge, 2);
            $colMerge = $this->shiftColumn($col, 8);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row); 
            $sheet->setCellValue($col . $row ,'Stock Disponible et Utilisable PN à l’inventaire en CARTON (SDU total BSD) (SDU PN en Carton au niveau BSD)');
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $colMerge = $this->shiftColumn($col, 8);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row); 
            $sheet->setCellValue($col . $row ,'Stock Disponible et Utilisable PN à l’inventaire en CARTON (SDU total CSB) (SDU PN en Carton au niveau CSB)'); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge, $row);

            $col = $this->shiftColumn($colMerge, 1);
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "TOTAL SDU PN en carton SDSP");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "SDU Fiche"); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "Nombre Total CSB"); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "Nombre CSB CRENAS");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "Taux de couverture CRENAS"); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "Nombre CSB CRENAS qui ont soumis leurs Commandes "); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);

            $col = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col. $row .':' . $col . $rowspan3);
            $sheet->setCellValue($col . $row, "Taux d'envoi de rapport bon de commande des CSB CRENAS"); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col, $rowspan3);
            
            // --------------------------------- Ligne 2 de désignation -------------------------------------------
        
            $row = $row + 1;
            $rowspan3 = $row + 2;
            $rowspan2 = $row + 1; 

            $col = "D";
            for ($i=0; $i < count($lstMoisAdmissionCRENASAnneePrecedent); $i++) {
                $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
                $sheet->setCellValue($col . $row, $lstMoisAdmissionCRENASAnneePrecedent[$i]);            
                $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
                $col = $this->shiftColumn($col, 1);
            }

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Total");        
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 1);

            for ($i=0; $i < count($lstMoisAdmissionProjeteAnneePrecedent); $i++) {
                $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
                $sheet->setCellValue($col . $row, $lstMoisAdmissionProjeteAnneePrecedent[$i]);
                
                $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
                $col = $this->shiftColumn($col, 1);
            } 

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Total");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 2);

            for ($i=0; $i < count($lstMoisProjectionAnneePrevisionnelle); $i++) {
                $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
                $sheet->setCellValue($col . $row, $lstMoisProjectionAnneePrevisionnelle[$i]);            
                $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
                $col = $this->shiftColumn($col, 1);
            } 

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Total");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 2);

            /* ---------- Quantité nécessaire en intrants nutrition ---------- */
            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "ATPE (en carton)");        
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "AMOX (boite de 100)");        
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Fiche patient (unité)");        
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Registre (unité)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);        
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Carnet de Rapport CRENAS (unité)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);        
            $col = $this->shiftColumn($col, 1);

            /* ---------- SDU AMOX Boite de 100 cp au niveau BSD ---------- */
            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot A");        
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot B");        
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot C");        
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot D");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Total");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 1); 

            /* ---------- SDU AMOX Boite de 100 cp au niveau CSB ---------- */

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot A");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot B");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot C");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot D");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Total");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 2); 

            /* ---------- SDU PN en Carton au niveau BSD ---------- */

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot A");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot B");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot C");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot D");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Total");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 1);

            /* ---------- SDU PN en Carton au niveau CSB ---------- */
            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot A");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot B");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot C");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $colMerge = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $colMerge . $row);
            $sheet->setCellValue($col . $row, "Lot D");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $colMerge , $row);
            $col = $this->shiftColumn($col, 2);

            $sheet->mergeCells($col . $row .':' . $col . $rowspan2);
            $sheet->setCellValue($col . $row, "Total");
            $this->styleApply($sheet, $col, $row, 1, 1, 0, $col , $rowspan2);
            $col = $this->shiftColumn($col, 1);

            // --------------------------------- Ligne 3 de désignation -------------------------------------------
            $col = "D";
            $col = $this->shiftColumn($col, $nombreMoisWithTotal); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0);

            $col = $this->shiftColumn($col, $nombreMoisWithTotal); 
            $this->styleApply($sheet, $col, $row, 1, 1, 0);

            $col = $this->shiftColumn($col, 1);
            $this->styleApply($sheet, $col, $row, 1, 1, 0);

            $col = $this->shiftColumn($col, $nombreMoisWithTotal);
            $this->styleApply($sheet, $col, $row, 1, 1, 0);

            $col = $this->shiftColumn($col, 1);
            $this->styleApply($sheet, $col, $row, 1, 1, 0);

            $col = $this->shiftColumn($col, 5);
            $this->styleApply($sheet, $col, $row, 1, 1, 0);

            $row = $row + 1; 

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            /* ------------------- */

            $col = $this->shiftColumn($col, 1); 
            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            
            /* ------------------- */ 
            $col = $this->shiftColumn($col, 2);
            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            /* ------------------- */ 
            $col = $this->shiftColumn($col, 1);
            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Quantité");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            $sheet->mergeCells($col . $row .':' . $col . $row);
            $sheet->setCellValue($col . $row, "Date d'expiration (jj/mm/yyyy)");
            $this->styleApply($sheet, $col, $row, 1, 1, 0);
            $col = $this->shiftColumn($col, 1);

            // --------------------------------- Ligne 4 de Donnée -------------------------------------------  
            
            $row = $row + 1; 
            if (isset($lstDataCrenasGroupe) && is_array($lstDataCrenasGroupe) && count($lstDataCrenasGroupe) > 0) {
                for ($j=0; $j < count($lstDataCrenasGroupe); $j++) {
                    $col = "A";
                    $dataCrenas = $lstDataCrenasGroupe[$j];
                    
                    /* ---------- Province - Région - District ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["nomProvince"]);      
                    $this->styleApply($sheet, $col, $row,0,0,1);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["nomRegion"]);     
                    $this->styleApply($sheet, $col, $row,0,0,1); 
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["nomDistrict"]);     
                    $this->styleApply($sheet, $col, $row,0,0,1);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Nombre d'admissions CRENAS enregistré ---------- */

                    for ($i=0; $i < count($lstMoisAdmissionCRENASAnneePrecedent); $i++) { 
                        $idDataCrenas = $dataCrenas["id"];
                        if (isset($lstValueMoisAdmissionCRENASAnneePrecedent[$idDataCrenas][$i])) {
                            $value = $lstValueMoisAdmissionCRENASAnneePrecedent[$idDataCrenas][$i]; 
                        } else {
                            $value = 0;
                        }
                        $sheet->mergeCells($col . $row .':' . $col . $row);
                        $sheet->setCellValue($col . $row, $value);     
                        $this->styleApply($sheet, $col, $row,0,1,0);
                        $col = $this->shiftColumn($col, 1); 
                    } 

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["totalCRENASAnneePrecedent"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Nombre d'admissions qui avait été projeté ---------- */

                    for ($i=0; $i < count($lstMoisAdmissionProjeteAnneePrecedent); $i++) { 
                        $idDataCrenas = $dataCrenas["id"];
                        if (isset($lstValueMoisAdmissionProjeteAnneePrecedent[$idDataCrenas][$i])) {
                            $value = $lstValueMoisAdmissionProjeteAnneePrecedent[$idDataCrenas][$i]; 
                        } else {
                            $value = 0;
                        }
                        $sheet->mergeCells($col . $row .':' . $col . $row);
                        $sheet->setCellValue($col . $row, $value);     
                        $this->styleApply($sheet, $col, $row,0,1,0); 
                        $col = $this->shiftColumn($col, 1); 
                    }

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["totalProjeteAnneePrecedent"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["resultatAnneePrecedent"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Projections du nombre d'admissions ---------- */

                    for ($i=0; $i < count($lstMoisProjectionAnneePrevisionnelle); $i++) { 
                        $idDataCrenas = $dataCrenas["id"];
                        if (isset($lstValueMoisProjectionAnneePrevisionnelle[$idDataCrenas][$i])) {
                            $value = $lstValueMoisProjectionAnneePrevisionnelle[$idDataCrenas][$i]; 
                        } else {
                            $value = 0;
                        }
                        $sheet->mergeCells($col . $row .':' . $col . $row);
                        $sheet->setCellValue($col . $row, $value);     
                        $this->styleApply($sheet, $col, $row,0,1,0); 
                        $col = $this->shiftColumn($col, 1); 
                    }

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["totalAnneeProjection"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["resultatAnneeProjection"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Quantité nécessaire en intrants nutrition ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["besoinAPTE"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["besoinAMOX"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["besoinFichePatient"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["besoinRegistre"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["besoinCarnetRapportCRENAS"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- SDU AMOX Boite de 100 cp au niveau BSD ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite01AmoxSDUCartonBSD"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration01AmoxSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite02AmoxSDUCartonBSD"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
    
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration02AmoxSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite03AmoxSDUCartonBSD"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
    
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration03AmoxSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite04AmoxSDUCartonBSD"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration03AmoxSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['totalAmoxSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1); 

                    /* ---------- SDU AMOX Boite de 100 cp au niveau CSB ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite01AmoxSDUCartonCSB"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration01AmoxSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite02AmoxSDUCartonCSB"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
    
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration02AmoxSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite03AmoxSDUCartonCSB"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
    
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration03AmoxSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite04AmoxSDUCartonCSB"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration04AmoxSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['totalAmoxSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
                    

                    /* ---------- TOTAL SDU AMOX Boite de 100 Cp SDSP ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['totalAmoxSDUCartonSDSP']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- SDU PN en Carton au niveau BSD ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite01PnSDUCartonBSD"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration01PnSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite02PnSDUCartonBSD"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
    
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration02PnSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite03PnSDUCartonBSD"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
    
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration03PnSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite04PnSDUCartonBSD"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration04PnSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['totalPnSDUCartonBSD']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);


                    /* ---------- SDU PN en Carton au niveau CSB ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite01PnSDUCartonCSB"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration01PnSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite02PnSDUCartonCSB"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
    
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration02PnSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite03PnSDUCartonCSB"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);
    
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration03PnSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, $dataCrenas["quantite04PnSDUCartonCSB"]);     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['dateExpiration04PnSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['totalPnSDUCartonCSB']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- TOTAL SDU PN en carton SDSP ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['totalPnSDUCartonSDSP']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- SDU Fiche ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['sduFiche']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Nombre Total CSB ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['nbrTotalCsb']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Nombre CSB CRENAS ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['nbrCSBCRENAS']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Taux de couverture CRENAS ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['tauxCouvertureCRENAS']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Nombre CSB CRENAS qui ont soumis leurs Commandes ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['nbrCSBCRENASCommande']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1);

                    /* ---------- Taux d'envoi de rapport bon de commande des CSB CRENAS  ---------- */
                    $sheet->mergeCells($col . $row .':' . $col . $row);
                    $sheet->setCellValue($col . $row, trim($dataCrenas['tauxEnvoiCommandeCSBCRENAS']));     
                    $this->styleApply($sheet, $col, $row,0,1,0);
                    $col = $this->shiftColumn($col, 1); 

                    $row = $row + 1;
                }
            } 


            // Créer un objet Writer pour sauvegarder le fichier Excel
            $writer = new Xlsx($spreadsheet);

            $nomGroupe = $dataGroupe[0]["nomGroupe"];
            $province = "";
            foreach ($dataGroupe as $groupe) {
                if ($province == "") {
                    $province = $groupe['provinceNomFR'];
                } else {
                    $province = $province . "-" . $groupe['provinceNomFR'];
                }
            } 

            $fileName = 'DATA_CRENAS_' . $nomGroupe . '_' . $province . '_' . date('d-m-Y Hi'). '.xlsx';

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
