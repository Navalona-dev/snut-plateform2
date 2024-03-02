<?php

namespace App\Controller\Admin2;

use App\Entity\Groupe;
use App\Form\GroupeType;
use App\Repository\DistrictRepository;
use App\Repository\GroupeRepository;
use App\Repository\ProvinceRepository;
use App\Service\GroupeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/groupe')]
class AdminGroupeController extends AbstractController
{
    #[Route('/', name: 'app_admin_groupe_index')]
    public function index()
    {
        return $this->render('admin/groupe/index.html.twig', [
        'controller_name' => 'AdminUserController',
        ]);
    }
    
    #[Route('/districts', name: 'app_admin_groupe_districts_by_provinces', methods: ['GET', 'POST'])]
    public function loadDistrictsByProvinces(Request $request, DistrictRepository $districtRepository)
    {
        $provinceIds = $request->get('provinceIds');
        $zone = $request->get('zone');
        
        $newZone = $zone;

        if ($zone == "" || $zone == null) {
            $newZone = null;
        }

        $districts = $districtRepository->getAllDistrictsByProvinces($provinceIds, $newZone);
      
        return new JsonResponse($districts);
    }

    #[Route('/ajax', name: 'app_admin_groupe_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, GroupeService $groupeService, EntityManagerInterface $entityManager): Response
    {
        $draw = intval($request->request->get('draw'));
        $start = $request->request->get('start');
        $length = $request->request->get('length');
        $search = $request->request->get('search');
        $orders = $request->request->get('order');
        $recherche = "";
    if (null != $search && $search != "") {
      $recherche = $search;
    }
    
    $groupes = $groupeService->getAllGroupe($entityManager, (int) $start, (int) $length, $recherche);
       
    $total = $groupeService->getNombreTotalGroupe($entityManager);
    $recordsTotal = (($total > 0) ? $total : 0);
    $tabgroupes = [];
    $tabgroupes["draw"] = $draw;
    $tabgroupes["recordsTotal"] = $recordsTotal;
    $tabgroupes["recordsFiltered"] = $recordsTotal;
    $tabgroupes["data"] = [];
    
    foreach ($groupes as $key => $groupe) {
      $Infogroupe = [];
      $Infogroupe['nom'] = $groupe['nom'];
      $Infogroupe['annee'] = $groupe['annee'];
      $Infogroupe['provinces'] = $groupe['provinces'];
      $Infogroupe['districts'] = $groupe['districts'];
      $Infogroupe['id'] = $groupe['id'];
      if (!in_array($Infogroupe, $tabgroupes["data"])) {
        array_push($tabgroupes["data"], $Infogroupe);
      }
    }
   
    return new JsonResponse($tabgroupes);
    }

    #[Route('/new', name: 'app_admin_groupe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $groupe = new Groupe();
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($groupe);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/groupe/new.html.twig', [
            'groupe' => $groupe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_groupe_show', methods: ['GET'])]
    public function show(Groupe $groupe): Response
    {
        return $this->render('admin/groupe/show.html.twig', [
            'groupe' => $groupe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_groupe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Groupe $groupe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_groupe_index', [], Response::HTTP_SEE_OTHER);
        }
        $districts = $groupe->getDistricts();
        $tabDistrict = [];
        if (count($districts) > 0) {
            foreach ($districts as $key => $district) {
                    if (!in_array($district->getId(), $tabDistrict)) {
                        array_push($tabDistrict, (string) $district->getId());
                    }
            }
        }

        return $this->render('admin/groupe/edit.html.twig', [
            'groupe' => $groupe,
            'form' => $form,
            'districtsGroupe' => $tabDistrict
        ]);
    }

    #[Route('/{id}', name: 'app_admin_groupe_delete', methods: ['POST'])]
    public function delete(Request $request, Groupe $groupe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$groupe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($groupe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_groupe_index', [], Response::HTTP_SEE_OTHER);
    }
}
