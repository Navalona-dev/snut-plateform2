<?php

namespace App\Controller\Admin2;

use App\Entity\CentreHospitalierUniversitaire;
use App\Form\CentreHospitalierUniversitaireType;
use App\Repository\CentreHospitalierUniversitaireRepository;
use App\Service\CentreHospitalierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/centre/hospitalier/universitaire')]
class AdminCentreHospitalierUniversitaireController extends AbstractController
{
    #[Route('/', name: 'app_centre_hospitalier_universitaire_index', methods: ['GET'])]
    public function index(CentreHospitalierUniversitaireRepository $centreHospitalierUniversitaireRepository): Response
    {
        return $this->render('admin/centre_hospitalier_universitaire/index.html.twig', [
            'centre_hospitalier_universitaires' => $centreHospitalierUniversitaireRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_centre_hosp_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, CentreHospitalierService $centreService, EntityManagerInterface $entityManager): Response
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
        
        $centres = $centreService->getAllCentre($entityManager, (int) $start, (int) $length, $recherche);
            
        $total = $centreService->getNombreTotalCentre($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabcentres = [];
        $tabcentres["draw"] = $draw;
        $tabcentres["recordsTotal"] = $recordsTotal;
        $tabcentres["recordsFiltered"] = $recordsTotal;
        $tabcentres["data"] = [];
        
        foreach ($centres as $key => $centre) {
        $Infocentre = [];
        $Infocentre['nom'] = $centre['nom'];
        /* $Infocentre['telephone'] = $centre['telephone'];
        $Infocentre['email'] = $centre['email'];*/
        $Infocentre['id'] = $centre['id'];
        if (!in_array($Infocentre, $tabcentres["data"])) {
            array_push($tabcentres["data"], $Infocentre);
        }
        }
    
        return new JsonResponse($tabcentres);
    }

    #[Route('/new', name: 'app_centre_hospitalier_universitaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $centreHospitalierUniversitaire = new CentreHospitalierUniversitaire();
        $form = $this->createForm(CentreHospitalierUniversitaireType::class, $centreHospitalierUniversitaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($centreHospitalierUniversitaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_centre_hospitalier_universitaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/centre_hospitalier_universitaire/new.html.twig', [
            'centre_hospitalier_universitaire' => $centreHospitalierUniversitaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_hospitalier_universitaire_show', methods: ['GET'])]
    public function show(CentreHospitalierUniversitaire $centreHospitalierUniversitaire): Response
    {
        return $this->render('admin/centre_hospitalier_universitaire/show.html.twig', [
            'centre_hospitalier_universitaire' => $centreHospitalierUniversitaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_centre_hospitalier_universitaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CentreHospitalierUniversitaire $centreHospitalierUniversitaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CentreHospitalierUniversitaireType::class, $centreHospitalierUniversitaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_centre_hospitalier_universitaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/centre_hospitalier_universitaire/edit.html.twig', [
            'centre_hospitalier_universitaire' => $centreHospitalierUniversitaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_hospitalier_universitaire_delete', methods: ['POST'])]
    public function delete(Request $request, CentreHospitalierUniversitaire $centreHospitalierUniversitaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$centreHospitalierUniversitaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($centreHospitalierUniversitaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_centre_hospitalier_universitaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
