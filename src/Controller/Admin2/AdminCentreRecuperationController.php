<?php

namespace App\Controller\Admin2;

use App\Entity\CentreRecuperation;
use App\Form\CentreRecuperationType;
use App\Repository\CentreRecuperationRepository;
use App\Service\CentreService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/centre/recuperation')]
class AdminCentreRecuperationController extends AbstractController
{
    #[Route('/', name: 'app_centre_recuperation_index', methods: ['GET'])]
    public function index(CentreRecuperationRepository $centreRecuperationRepository): Response
    {
        return $this->render('admin/centre_recuperation/index.html.twig', [
            'centre_recuperations' => $centreRecuperationRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_centre_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, CentreService $centreService, EntityManagerInterface $entityManager): Response
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

    #[Route('/new', name: 'app_centre_recuperation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $centreRecuperation = new CentreRecuperation();
        $form = $this->createForm(CentreRecuperationType::class, $centreRecuperation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($centreRecuperation);
            $entityManager->flush();

            return $this->redirectToRoute('app_centre_recuperation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/centre_recuperation/new.html.twig', [
            'centre_recuperation' => $centreRecuperation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_recuperation_show', methods: ['GET'])]
    public function show(CentreRecuperation $centreRecuperation): Response
    {
        return $this->render('admin/centre_recuperation/show.html.twig', [
            'centre_recuperation' => $centreRecuperation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_centre_recuperation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CentreRecuperation $centreRecuperation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CentreRecuperationType::class, $centreRecuperation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_centre_recuperation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/centre_recuperation/edit.html.twig', [
            'centre_recuperation' => $centreRecuperation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_recuperation_delete', methods: ['POST'])]
    public function delete(Request $request, CentreRecuperation $centreRecuperation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$centreRecuperation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($centreRecuperation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_centre_recuperation_index', [], Response::HTTP_SEE_OTHER);
    }
}
