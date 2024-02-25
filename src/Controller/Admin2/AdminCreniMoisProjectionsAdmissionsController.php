<?php

namespace App\Controller\Admin2;

use App\Entity\CreniMoisProjectionsAdmissions;
use App\Form\CreniMoisProjectionsAdmissionsType;
use App\Repository\CreniMoisProjectionsAdmissionsRepository;
use App\Service\MoisProjectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/creni/mois/projections/admissions')]
class AdminCreniMoisProjectionsAdmissionsController extends AbstractController
{
    #[Route('/', name: 'app_creni_mois_projections_admissions_index', methods: ['GET'])]
    public function index(CreniMoisProjectionsAdmissionsRepository $creniMoisProjectionsAdmissionsRepository): Response
    {
        return $this->render('admin/creni_mois_projections_admissions/index.html.twig', [
            'creni_mois_projections_admissions' => $creniMoisProjectionsAdmissionsRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_moisprojection_semestrielle_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, MoisProjectionService $moisprojectionService, EntityManagerInterface $entityManager): Response
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
        
        $moisprojections = $moisprojectionService->getAllMoisProjectionSemestrielle($entityManager, (int) $start, (int) $length, $recherche);
            
        $total = $moisprojectionService->getNombreTotalMoisProjectionSemestrielle($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabmoisprojections = [];
        $tabmoisprojections["draw"] = $draw;
        $tabmoisprojections["recordsTotal"] = $recordsTotal;
        $tabmoisprojections["recordsFiltered"] = $recordsTotal;
        $tabmoisprojections["data"] = [];
        
        foreach ($moisprojections as $key => $moisprojection) {
        $Infomoisprojection = [];
        $Infomoisprojection['nom'] = $moisprojection['Nom'];
        /* $Infomoisprojection['telephone'] = $moisprojection['telephone'];
        $Infomoisprojection['email'] = $moisprojection['email'];*/
        $Infomoisprojection['id'] = $moisprojection['id'];
        if (!in_array($Infomoisprojection, $tabmoisprojections["data"])) {
            array_push($tabmoisprojections["data"], $Infomoisprojection);
        }
        }
    
        return new JsonResponse($tabmoisprojections);
    }

    #[Route('/new', name: 'app_creni_mois_projections_admissions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $creniMoisProjectionsAdmission = new CreniMoisProjectionsAdmissions();
        $form = $this->createForm(CreniMoisProjectionsAdmissionsType::class, $creniMoisProjectionsAdmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($creniMoisProjectionsAdmission);
            $entityManager->flush();

            return $this->redirectToRoute('app_creni_mois_projections_admissions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/creni_mois_projections_admissions/new.html.twig', [
            'creni_mois_projections_admission' => $creniMoisProjectionsAdmission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creni_mois_projections_admissions_show', methods: ['GET'])]
    public function show(CreniMoisProjectionsAdmissions $creniMoisProjectionsAdmission): Response
    {
        return $this->render('admin/creni_mois_projections_admissions/show.html.twig', [
            'creni_mois_projections_admission' => $creniMoisProjectionsAdmission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_creni_mois_projections_admissions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CreniMoisProjectionsAdmissions $creniMoisProjectionsAdmission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreniMoisProjectionsAdmissionsType::class, $creniMoisProjectionsAdmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_creni_mois_projections_admissions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/creni_mois_projections_admissions/edit.html.twig', [
            'creni_mois_projections_admission' => $creniMoisProjectionsAdmission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creni_mois_projections_admissions_delete', methods: ['POST'])]
    public function delete(Request $request, CreniMoisProjectionsAdmissions $creniMoisProjectionsAdmission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$creniMoisProjectionsAdmission->getId(), $request->request->get('_token'))) {
            $entityManager->remove($creniMoisProjectionsAdmission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_creni_mois_projections_admissions_index', [], Response::HTTP_SEE_OTHER);
    }
}
