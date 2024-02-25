<?php

namespace App\Controller\Admin2;

use App\Entity\Province;
use App\Form\ProvinceType;
use App\Repository\ProvinceRepository;
use App\Service\ProvinceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/province')]
class AdminProvinceController extends AbstractController
{
    #[Route('/', name: 'app_admin_province_index', methods: ['GET'])]
    public function index(ProvinceRepository $provinceRepository): Response
    {
        return $this->render('admin/province/index.html.twig', [
            'provinces' => $provinceRepository->findAll(),
        ]);
    }
    
    #[Route('/ajax', name: 'app_admin_province_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, ProvinceService $provinceService, EntityManagerInterface $entityManager): Response
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
        
        $provinces = $provinceService->getAllProvince($entityManager, (int) $start, (int) $length, $recherche);
            
        $total = $provinceService->getNombreTotalProvince($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabprovinces = [];
        $tabprovinces["draw"] = $draw;
        $tabprovinces["recordsTotal"] = $recordsTotal;
        $tabprovinces["recordsFiltered"] = $recordsTotal;
        $tabprovinces["data"] = [];
        
        foreach ($provinces as $key => $province) {
        $Infoprovince = [];
        $Infoprovince['nom'] = $province['nom_fr'];
        /* $Infoprovince['telephone'] = $province['telephone'];
        $Infoprovince['email'] = $province['email'];*/
        $Infoprovince['id'] = $province['id'];
        if (!in_array($Infoprovince, $tabprovinces["data"])) {
            array_push($tabprovinces["data"], $Infoprovince);
        }
        }
    
        return new JsonResponse($tabprovinces);
    }

    #[Route('/new', name: 'app_admin_province_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $province = new Province();
        $form = $this->createForm(ProvinceType::class, $province);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($province);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_province_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/province/new.html.twig', [
            'province' => $province,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_province_show', methods: ['GET'])]
    public function show(Province $province): Response
    {
        return $this->render('admin/province/show.html.twig', [
            'province' => $province,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_province_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Province $province, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProvinceType::class, $province);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_province_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/province/edit.html.twig', [
            'province' => $province,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_province_delete', methods: ['POST'])]
    public function delete(Request $request, Province $province, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$province->getId(), $request->request->get('_token'))) {
            $entityManager->remove($province);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_province_index', [], Response::HTTP_SEE_OTHER);
    }
}
