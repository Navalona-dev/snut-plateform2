<?php

namespace App\Controller;

use App\Entity\CommandeSemestrielle;
use App\Entity\CommandeTrimestrielle;
use App\Entity\District;
use App\Entity\Produit;
use App\Entity\Pvrd;
use App\Entity\PvrdProduit;
use App\Entity\Region;
use App\Finder\CommandeSemestrielleFinder;
use App\Finder\CommandeTrimestrielleFinder;
use App\Finder\ProduitFinder;
use App\Finder\PvrdFinder;
use App\Finder\PvrdProduitFinder;
use App\Finder\UserFinder;
use App\Repository\CommandeTrimestrielleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface; 
use Doctrine\DBAL\Logging\EchoSQLLogger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PvrdController extends AbstractController
{
    private $_userService;
    private $_produit;
    private $_commandeTrimestrielle;
    private $_commandeSemestrielle;
    private $_pvrd_service;
    private $_pvrd_produit_service;
    
    public function __construct(UserFinder $user_service_container, ProduitFinder $produit, CommandeTrimestrielleFinder $commande_trimestrielle, CommandeSemestrielleFinder $commande_semestrielle, PvrdFinder $pvrd_service_container, PvrdProduitFinder $pvrd_produit_service_container) 
    {
        $this->_userService = $user_service_container;
        $this->_produit = $produit;
        $this->_commandeTrimestrielle = $commande_trimestrielle;
        $this->_commandeSemestrielle = $commande_semestrielle;
        $this->_pvrd_service = $pvrd_service_container;
        $this->_pvrd_produit_service = $pvrd_produit_service_container; 
    }

    #[Route('/pvrd', name: 'app_pvrd')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) { 
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataProduit = $this->_produit->findAllProduct();
            $dataPeriode1 = $this->_commandeTrimestrielle->findDataCommandeTrimestrielle();
            $dataPeriode2 = $this->_commandeSemestrielle->findDataCommandeSemestrielle();
            $dataPeriode = [$dataPeriode1, $dataPeriode2]; 
            //$dataPvrd = $this->_pvrd_service->findDataPvrdByUserCommandeTrimestrielle($userId, 1);
            return $this->render('pvrd/homePvrd.html.twig', [
                'controller_name' => 'PvrdController',
                "dataUser" => $dataUser,
                "dataProduit" => $dataProduit,
                "dataPeriode" => $dataPeriode,
                "dataPvrd" => null
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/liste/pvrd', name: 'liste_pvrd')]
    public function listePvrd(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user) { 
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $districtId = $user->getDistrict()->getId();
            $lstPvrdDistrict = $this->_pvrd_service->findListPvrdByDistrict($districtId);
          /*  $District = $entityManager->getRepository(Region::class)->find($regionId);
            if (!$Region) {
                throw $this->createNotFoundException('La région n\'existe pas.');
            }*/
          
            return $this->render('pvrd/listePvrd.html.twig', [
                "mnuActive" => "Pvrd",
                "dataUser" => $dataUser,
                "District" => $user->getDistrict(),
                "lstPvrdDistrict" => $lstPvrdDistrict,
                "numberOfDistrictSendPvrd" => count($lstPvrdDistrict),
                "numberOfDistricts" => null,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/pvrd/save', name: 'app_pvrd_save')]
    public function save(Request $request, EntityManagerInterface $entityManager, CommandeTrimestrielleRepository $commandeTrimestrielleRepository): Response 
    {
        $user = $this->getUser();
        if ($user) { 
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            if ($request->isMethod('POST')) {

                $Site = $request->request->get('Site');
                $DateReception = $request->request->get('DateReception');
                $DateReception = DateTime::createFromFormat('Y-m-d', $DateReception);

                $DatePvrd = $request->request->get('DatePvrd');
                $DatePvrd = DateTime::createFromFormat('Y-m-d', $DatePvrd);

                $dateActuelle = new DateTime();
                $dateFormatee = $dateActuelle->format('Y-m-d');
                $DateTeleversement = new DateTime($dateFormatee);

                $NumeroBonLivraison = $request->request->get('NumeroBonLivraison');
                $Fournisseur = $request->request->get('Fournisseur'); 

                
                 
                $file = $request->files->get('pvrd_file'); // Assurez-vous que 'pvrd_file' correspond au nom de votre champ de téléversement dans le formulaire.
                
                if ($file instanceof UploadedFile) {
                    // Vérifiez l'extension du fichier (assurez-vous d'ajuster les extensions autorisées selon vos besoins).
                    $allowedExtensions = ['jpg', 'png', 'doc', 'docx'];
                    $extensionOriginalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                    if (!in_array($extensionOriginalFilename, $allowedExtensions)) {
                        // Redirigez l'utilisateur avec un message d'erreur en cas d'extension non autorisée.
                        $this->addFlash('error', '<strong>Erreur de fichier téléverser</strong><br/> Le format accepté est le format <b>Image</b> au format <b>.jpg</b> ou <b>.png</b> .');
                        return $this->redirectToRoute('app_pvrd');
                    }

                    // Générez un nom de fichier unique en utilisant le format souhaité.
                    // PVRD_DISTRICT_PERIODE_DATETELEVERSEMENT_NOM
                    $NomUser = preg_replace('/[^a-zA-Z0-9_]/', '', $dataUser["nomUser"]);
                    $NomDistrict = strtolower($dataUser["nomDistrict"]); 

                    $newFileName = "PVRD_" . $NomDistrict . "_" . $dateActuelle->format('Ymd') . '_'  . $NomUser. '.'  . $extensionOriginalFilename ;
                    
                    $mappingConfig = $this->getParameter('vich_uploader.mappings')['uploads_pvrd'];
                    // Accédez au répertoire de destination depuis la configuration
                    $uploadDestination = $mappingConfig['upload_destination'];

                    $file->move(
                        $uploadDestination, // Le répertoire de destination configuré dans VichUploader
                        $newFileName
                    );

                    $District = $entityManager->getRepository(District::class)->find($dataUser["idDistrict"]);
                    $Region = $entityManager->getRepository(Region::class)->find($dataUser["idRegion"]);
                    $currentCommande = $commandeTrimestrielleRepository->findOneBy(['isActive' => true]);
                    $pvrd = new Pvrd();
                    $pvrd->setCommandeTrimestrielle($currentCommande);
                    $pvrd->setResponsableDistrict($user);
                    $pvrd->setSite($Site);
                    $pvrd->setDateReception($DateReception);
                    $pvrd->setDatePvrd($DatePvrd);
                    $pvrd->setDateTeleversement($DateTeleversement);
                    $pvrd->setNumeroBonLivraison($NumeroBonLivraison);
                    $pvrd->setFournisseur($Fournisseur);
                    $pvrd->setOriginalFileName($file->getClientOriginalName());
                    $pvrd->setNewFileName($newFileName);             
                    $pvrd->setUploadedDateTime(new \DateTime());
                    $pvrd->setDistrict($District);
                    $pvrd->setRegion($Region); 

                    $entityManager->persist($pvrd);
                    $entityManager->flush();
 
                    $lstDesignation = $_POST['designation']; 
                    $lstQuantiteBl = $_POST['quantiteBl'];
                    $lstQuantiteRecue = $_POST['quantiteRecue'];
                    $lstQuantiteEcart = $_POST['quantiteEcart'];
                    $lstPeriode = $_POST['periode'];
 
                    // Assurez-vous que les trois tableaux ont la même longueur
                    $count = (is_array($lstDesignation) && count($lstDesignation) > 0) ? count($lstDesignation) : 0;
                    
                    if ((is_array($lstQuantiteBl) && count($lstQuantiteBl) === $count) && (is_array($lstQuantiteRecue) && count($lstQuantiteRecue) === $count)) {
                        for ($i = 0; $i < $count; $i++) {
                            // Utilisez directement les valeurs du tableau sans convertir
                            $EcartEntreQuantite = $lstQuantiteBl[$i] - $lstQuantiteRecue[$i];
                            $idProduit = $lstDesignation[$i];
                            $Produit = $entityManager->getRepository(Produit::class)->find($idProduit);
                            
                            $PvrdProduit = new PvrdProduit();
                            $PvrdProduit->setPvrd($pvrd);
                            $PvrdProduit->setProduit($Produit);
                            $PvrdProduit->setPeriode($lstPeriode[$i]);
                            $PvrdProduit->setQuantiteInscritSurBL($lstQuantiteBl[$i]);
                            $PvrdProduit->setQuantiteRecue($lstQuantiteRecue[$i]);
                            $PvrdProduit->setEcartEntreQuantite($EcartEntreQuantite);

                            $entityManager->persist($PvrdProduit);
                        }
                        // Effectuez le flush une fois après la boucle for
                        $entityManager->flush();
                    } else {
                        // Gérer l'incohérence des longueurs des tableaux
                        // Par exemple : afficher un message d'erreur ou logguer un avertissement
                    } 
                    $dataPeriode1 = $this->_commandeTrimestrielle->findDataCommandeTrimestrielle();
                    $dataPeriode2 = $this->_commandeSemestrielle->findDataCommandeSemestrielle();
                    $dataPeriode = [$dataPeriode1, $dataPeriode2]; 
                    $dataProduit = $this->_produit->findAllProduct();
                } 
 
                return $this->redirectToRoute('app_pvrd');

            } 
            return $this->redirectToRoute('app_accueil'); 
        } else { 
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/pvrd/detail/{id}', name: 'detail_pvrd')]
    public function voirPvrd(Pvrd $pvrd): Response
    {
        $user = $this->getUser();
        if ($user) { 
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataProduit = $this->_produit->findAllProduct();
            $dataPeriode1 = $this->_commandeTrimestrielle->findDataCommandeTrimestrielle();
            $dataPeriode2 = $this->_commandeSemestrielle->findDataCommandeSemestrielle();
            $dataPeriode = [$dataPeriode1, $dataPeriode2]; 
            $dataPvrd = $this->_pvrd_service->findDataPvrdByUserCommandeTrimestrielle($userId, 1, $pvrd->getId());
            $dataPvrdProduit = $this->_pvrd_produit_service->findDataPvrdProduitByIdPvrd($pvrd->getId()); 
            return $this->render('pvrd/pvrdDistrict.html.twig', [
                'controller_name' => 'PvrdController',
                "dataUser" => $dataUser,
                "dataProduit" => $dataProduit,
                "dataPeriode" => $dataPeriode,
                "dataPvrd" => $dataPvrd,
                "mnuActive" => "Pvrd",
                "dataPvrdProduit" => $dataPvrdProduit
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/supervisor/pvrd/region/{regionId}', name: 'app_sp_pvrd_region')]
    public function listePvrdRegion($regionId, EntityManagerInterface $entityManager, CommandeTrimestrielleRepository $commandeTrimestrielleRepository)
    {
        $user = $this->getUser();
        if ($user) {
            $currentCommande = $commandeTrimestrielleRepository->findOneBy(['isActive' => true]);
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $lstPvrdRegion = $this->_pvrd_service->findListPvrdByRegion($regionId);
            $Region = $entityManager->getRepository(Region::class)->find($regionId);
            if (!$Region) {
                throw $this->createNotFoundException('La région n\'existe pas.');
            }
            // Obtenez le nombre total de districts dans la région
            $numberOfDistricts = $Region->getDistricts()->count();
            $districtsDataPvrd = $this->_pvrd_service->getDistrictsDataPvrdByRegion($regionId);
          
            return $this->render('supervisor/supervisorCentralPvrdRegion.html.twig', [
                "mnuActive" => "Pvrd",
                "dataUser" => $dataUser,
                "dataRegion" => $Region,
                "lstPvrdRegion" => $lstPvrdRegion,
                "numberOfDistrictSendPvrd" => count($lstPvrdRegion),
                "numberOfDistricts" => $numberOfDistricts,
                "districtsDataPvrd" => $districtsDataPvrd,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/supervisor/pvrd/responsableDistrict/{responsableDistrict}', name: 'app_supervisor_pvrd_detail_district')]
    public function detailPvrdDistrict($responsableDistrict)
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataPvrd = $this->_pvrd_service->findDataPvrdByUserCommandeTrimestrielle($responsableDistrict, 1, null);
         
            $dataPvrdProduit = $this->_pvrd_produit_service->findDataPvrdProduitByIdPvrd($dataPvrd["IdPvrd"]); 
            return $this->render('supervisor/supervisorCentralPvrdDistrict.html.twig', [
                "mnuActive" => "Pvrd",
                "dataUser" => $dataUser,
                "dataPvrd" => $dataPvrd,
                "dataPvrdProduit" => $dataPvrdProduit
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/supervisor/pvrd/detail/{id}', name: 'app_supervisor_pvrd_detail')]
    public function pvrdDistrictDetail(Pvrd $pvrd)
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);
            $dataPvrd = $this->_pvrd_service->findDataPvrdCommandeTrimestrielle($pvrd->getId());
           // dd($dataPvrd);
            $dataPvrdProduit = $this->_pvrd_produit_service->findDataPvrdProduitByIdPvrd($pvrd->getId()); 
            return $this->render('supervisor/supervisorCentralPvrdDistrict.html.twig', [
                "mnuActive" => "Pvrd",
                "dataUser" => $dataUser,
                "dataPvrd" => $dataPvrd,
                "dataPvrdProduit" => $dataPvrdProduit
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
