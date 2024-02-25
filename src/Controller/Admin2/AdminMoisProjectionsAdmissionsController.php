<?php

namespace App\Controller\Admin2;

use App\Entity\MoisProjectionsAdmissions;
use App\Form\MoisProjectionsAdmissionsType;
use App\Repository\MoisProjectionsAdmissionsRepository;
use App\Service\MoisProjectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/mois/projections/admissions')]
class AdminMoisProjectionsAdmissionsController extends AbstractController
{
    #[Route('/', name: 'app_mois_projections_admissions_index', methods: ['GET'])]
    public function index(MoisProjectionsAdmissionsRepository $moisProjectionsAdmissionsRepository): Response
    {
        return $this->render('admin/mois_projections_admissions/index.html.twig', [
            'mois_projections_admissions' => $moisProjectionsAdmissionsRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_moisprojection_trimestrielle_liste', methods: ['GET', 'POST'])]
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
        
        $moisprojections = $moisprojectionService->getAllMoisProjectionTrimestrielle($entityManager, (int) $start, (int) $length, $recherche);
         
        $total = $moisprojectionService->getNombreTotalMoisProjectionTrimestrielle($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabmoisprojections = [];
        $tabmoisprojections["draw"] = $draw;
        $tabmoisprojections["recordsTotal"] = $recordsTotal;
        $tabmoisprojections["recordsFiltered"] = $recordsTotal;
        $tabmoisprojections["data"] = [];
        
        foreach ($moisprojections as $key => $moisprojection) {
        $Infomoisprojection = [];
        $Infomoisprojection['nomGroupe'] = $moisprojection['nomGroupe'];
        $Infomoisprojection['nomCommande'] = $moisprojection['nomCommande'];
        $Infomoisprojection['MoisAdmissionCRENASAnneePrecedent'] = $moisprojection['MoisAdmissionCRENASAnneePrecedent'];
        $Infomoisprojection['id'] = $moisprojection['id'];
        $Infomoisprojection['MoisAdmissionProjeteAnneePrecedent'] = $moisprojection['MoisAdmissionProjeteAnneePrecedent'];
        $Infomoisprojection['MoisProjectionAnneePrevisionnelle'] = $moisprojection['MoisProjectionAnneePrevisionnelle'];
        if (!in_array($Infomoisprojection, $tabmoisprojections["data"])) {
            array_push($tabmoisprojections["data"], $Infomoisprojection);
        }
        }
    
        return new JsonResponse($tabmoisprojections);
    }

    #[Route('/new', name: 'app_mois_projections_admissions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $moisProjectionsAdmission = new MoisProjectionsAdmissions();
        $form = $this->createForm(MoisProjectionsAdmissionsType::class, $moisProjectionsAdmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($moisProjectionsAdmission);
            $entityManager->flush();

            return $this->redirectToRoute('app_mois_projections_admissions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/mois_projections_admissions/new.html.twig', [
            'mois_projections_admission' => $moisProjectionsAdmission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mois_projections_admissions_show', methods: ['GET'])]
    public function show(MoisProjectionsAdmissions $moisProjectionsAdmission): Response
    {
        return $this->render('admin/mois_projections_admissions/show.html.twig', [
            'mois_projections_admission' => $moisProjectionsAdmission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mois_projections_admissions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MoisProjectionsAdmissions $moisProjectionsAdmission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MoisProjectionsAdmissionsType::class, $moisProjectionsAdmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mois_projections_admissions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/mois_projections_admissions/edit.html.twig', [
            'mois_projections_admission' => $moisProjectionsAdmission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mois_projections_admissions_delete', methods: ['POST'])]
    public function delete(Request $request, MoisProjectionsAdmissions $moisProjectionsAdmission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moisProjectionsAdmission->getId(), $request->request->get('_token'))) {
            $entityManager->remove($moisProjectionsAdmission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mois_projections_admissions_index', [], Response::HTTP_SEE_OTHER);
    }
}
