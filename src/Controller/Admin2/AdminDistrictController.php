<?php

namespace App\Controller\Admin2;

use App\Entity\District;
use App\Form\DistrictType;
use App\Repository\DistrictRepository;
use App\Service\DistrictService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/district')]
class AdminDistrictController extends AbstractController
{
    #[Route('/', name: 'app_district_index', methods: ['GET'])]
    public function index(DistrictRepository $districtRepository): Response
    {
        return $this->render('admin/district/index.html.twig', [
            'districts' => $districtRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_district_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, DistrictService $districtService, EntityManagerInterface $entityManager): Response
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
        
        $districts = $districtService->getAllDistrict($entityManager, (int) $start, (int) $length, $recherche);
            
        $total = $districtService->getNombreTotalDistrict($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabdistricts = [];
        $tabdistricts["draw"] = $draw;
        $tabdistricts["recordsTotal"] = $recordsTotal;
        $tabdistricts["recordsFiltered"] = $recordsTotal;
        $tabdistricts["data"] = [];
        
        foreach ($districts as $key => $district) {
        $Infodistrict = [];
        $Infodistrict['nom'] = $district['nom'];
        /* $Infodistrict['telephone'] = $district['telephone'];
        $Infodistrict['email'] = $district['email'];*/
        $Infodistrict['id'] = $district['id'];
        if (!in_array($Infodistrict, $tabdistricts["data"])) {
            array_push($tabdistricts["data"], $Infodistrict);
        }
        }
    
        return new JsonResponse($tabdistricts);
    }

    #[Route('/new', name: 'app_district_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $district = new District();
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($district);
            $entityManager->flush();

            return $this->redirectToRoute('app_district_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/district/new.html.twig', [
            'district' => $district,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_district_show', methods: ['GET'])]
    public function show(District $district): Response
    {
        return $this->render('admin/district/show.html.twig', [
            'district' => $district,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_district_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, District $district, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_district_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/district/edit.html.twig', [
            'district' => $district,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_district_delete', methods: ['POST'])]
    public function delete(Request $request, District $district, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$district->getId(), $request->request->get('_token'))) {
            $entityManager->remove($district);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_district_index', [], Response::HTTP_SEE_OTHER);
    }
}
