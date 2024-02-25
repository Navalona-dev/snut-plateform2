<?php

namespace App\Controller\Admin2;

use App\Entity\AnneePrevisionnelle;
use App\Form\AnneePrevisionnelleType;
use App\Repository\AnneePrevisionnelleRepository;
use App\Service\AnneeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/annee/previsionnelle')]
class AdminAnneePrevisionnelleController extends AbstractController
{
    #[Route('/', name: 'app_annee_previsionnelle_index', methods: ['GET'])]
    public function index(AnneePrevisionnelleRepository $anneePrevisionnelleRepository): Response
    {
        return $this->render('admin/annee_previsionnelle/index.html.twig', [
            'annee_previsionnelles' => $anneePrevisionnelleRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_annee_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, AnneeService $anneeService, EntityManagerInterface $entityManager): Response
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
        
        $annees = $anneeService->getAllAnnee($entityManager, (int) $start, (int) $length, $recherche);
            
        $total = $anneeService->getNombreTotalAnnee($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabannees = [];
        $tabannees["draw"] = $draw;
        $tabannees["recordsTotal"] = $recordsTotal;
        $tabannees["recordsFiltered"] = $recordsTotal;
        $tabannees["data"] = [];
        
        foreach ($annees as $key => $annee) {
        $Infoannee = [];
        $Infoannee['nom'] = $annee['annee'];
        /* $Infoannee['telephone'] = $annee['telephone'];
        $Infoannee['email'] = $annee['email'];*/
        $Infoannee['id'] = $annee['id'];
        if (!in_array($Infoannee, $tabannees["data"])) {
            array_push($tabannees["data"], $Infoannee);
        }
        }
    
        return new JsonResponse($tabannees);
    }

    #[Route('/new', name: 'app_annee_previsionnelle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $anneePrevisionnelle = new AnneePrevisionnelle();
        $form = $this->createForm(AnneePrevisionnelleType::class, $anneePrevisionnelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($anneePrevisionnelle);
            $entityManager->flush();

            return $this->redirectToRoute('app_annee_previsionnelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/annee_previsionnelle/new.html.twig', [
            'annee_previsionnelle' => $anneePrevisionnelle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annee_previsionnelle_show', methods: ['GET'])]
    public function show(AnneePrevisionnelle $anneePrevisionnelle): Response
    {
        return $this->render('admin/annee_previsionnelle/show.html.twig', [
            'annee_previsionnelle' => $anneePrevisionnelle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annee_previsionnelle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AnneePrevisionnelle $anneePrevisionnelle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnneePrevisionnelleType::class, $anneePrevisionnelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_annee_previsionnelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/annee_previsionnelle/edit.html.twig', [
            'annee_previsionnelle' => $anneePrevisionnelle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annee_previsionnelle_delete', methods: ['POST'])]
    public function delete(Request $request, AnneePrevisionnelle $anneePrevisionnelle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$anneePrevisionnelle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($anneePrevisionnelle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annee_previsionnelle_index', [], Response::HTTP_SEE_OTHER);
    }
}
