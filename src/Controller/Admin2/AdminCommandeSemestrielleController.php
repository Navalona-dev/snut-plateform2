<?php

namespace App\Controller\Admin2;

use App\Entity\CommandeSemestrielle;
use App\Form\CommandeSemestrielleType;
use App\Repository\CommandeSemestrielleRepository;
use App\Service\CommandeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/commande/semestrielle')]
class AdminCommandeSemestrielleController extends AbstractController
{
    #[Route('/', name: 'app_commande_semestrielle_index', methods: ['GET'])]
    public function index(CommandeSemestrielleRepository $commandeSemestrielleRepository): Response
    {
        return $this->render('admin/commande_semestrielle/index.html.twig', [
            'commande_semestrielles' => $commandeSemestrielleRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_commande_semestrielle_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, CommandeService $commandeService, EntityManagerInterface $entityManager): Response
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
        
        $commandes = $commandeService->getAllCommandeSemestrielle($entityManager, (int) $start, (int) $length, $recherche);
            
        $total = $commandeService->getNombreTotalCommandeSemestrielle($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabcommandes = [];
        $tabcommandes["draw"] = $draw;
        $tabcommandes["recordsTotal"] = $recordsTotal;
        $tabcommandes["recordsFiltered"] = $recordsTotal;
        $tabcommandes["data"] = [];
        
        foreach ($commandes as $key => $commande) {
        $Infocommande = [];
        $Infocommande['nom'] = $commande['Nom'];
         $Infocommande['Annee'] = $commande['Annee'];
        $Infocommande['DateDebut'] = $commande['DateDebut']->format('d-m-Y');
        $Infocommande['DateFin'] = $commande['DateFin']->format('d-m-Y');
        $Infocommande['isActive'] = (($commande['IsActive'] && $commande['IsActive'] != null) ? "Actif" : "Inactif");
        $Infocommande['Slug'] = $commande['Slug'];
        $Infocommande['id'] = $commande['id'];
        $Infocommande['id'] = $commande['id'];
        if (!in_array($Infocommande, $tabcommandes["data"])) {
            array_push($tabcommandes["data"], $Infocommande);
        }
        }
    
        return new JsonResponse($tabcommandes);
    }

    #[Route('/new', name: 'app_commande_semestrielle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commandeSemestrielle = new CommandeSemestrielle();
        $form = $this->createForm(CommandeSemestrielleType::class, $commandeSemestrielle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commandeSemestrielle);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_semestrielle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/commande_semestrielle/new.html.twig', [
            'commande_semestrielle' => $commandeSemestrielle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_semestrielle_show', methods: ['GET'])]
    public function show(CommandeSemestrielle $commandeSemestrielle): Response
    {
        return $this->render('admin/commande_semestrielle/show.html.twig', [
            'commande_semestrielle' => $commandeSemestrielle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_semestrielle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommandeSemestrielle $commandeSemestrielle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeSemestrielleType::class, $commandeSemestrielle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_semestrielle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/commande_semestrielle/edit.html.twig', [
            'commande_semestrielle' => $commandeSemestrielle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_semestrielle_delete', methods: ['POST'])]
    public function delete(Request $request, CommandeSemestrielle $commandeSemestrielle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandeSemestrielle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commandeSemestrielle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_semestrielle_index', [], Response::HTTP_SEE_OTHER);
    }
}
