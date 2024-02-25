<?php

namespace App\Controller\Admin2;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use App\Service\RegionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/region')]
class AdminRegionController extends AbstractController
{
    #[Route('/', name: 'app_admin_region_index', methods: ['GET'])]
    public function index(RegionRepository $regionRepository): Response
    {
        return $this->render('admin/region/index.html.twig', [
            'regions' => $regionRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_region_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, RegionService $regionService, EntityManagerInterface $entityManager): Response
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
        
        $regions = $regionService->getAllRegion($entityManager, (int) $start, (int) $length, $recherche);
            
        $total = $regionService->getNombreTotalRegion($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabregions = [];
        $tabregions["draw"] = $draw;
        $tabregions["recordsTotal"] = $recordsTotal;
        $tabregions["recordsFiltered"] = $recordsTotal;
        $tabregions["data"] = [];
        
        foreach ($regions as $key => $region) {
        $Inforegion = [];
        $Inforegion['nom'] = $region['nom'];
        /* $Inforegion['telephone'] = $region['telephone'];
        $Inforegion['email'] = $region['email'];*/
        $Inforegion['id'] = $region['id'];
        if (!in_array($Inforegion, $tabregions["data"])) {
            array_push($tabregions["data"], $Inforegion);
        }
        }
    
        return new JsonResponse($tabregions);
    }
    
    #[Route('/new', name: 'app_admin_region_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($region);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_region_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/region/new.html.twig', [
            'region' => $region,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_region_show', methods: ['GET'])]
    public function show(Region $region): Response
    {
        return $this->render('admin/region/show.html.twig', [
            'region' => $region,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_region_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Region $region, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_region_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/region/edit.html.twig', [
            'region' => $region,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_region_delete', methods: ['POST'])]
    public function delete(Request $request, Region $region, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$region->getId(), $request->request->get('_token'))) {
            $entityManager->remove($region);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_region_index', [], Response::HTTP_SEE_OTHER);
    }
}
