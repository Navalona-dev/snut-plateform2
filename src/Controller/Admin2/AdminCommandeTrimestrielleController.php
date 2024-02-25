<?php

namespace App\Controller\Admin2;

use App\Entity\CommandeTrimestrielle;
use App\Form\CommandeTrimestrielleType;
use App\Repository\CommandeTrimestrielleRepository;
use App\Service\CommandeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/commande/trimestrielle')]
class AdminCommandeTrimestrielleController extends AbstractController
{
    #[Route('/', name: 'app_commande_trimestrielle_index', methods: ['GET'])]
    public function index(CommandeTrimestrielleRepository $commandeTrimestrielleRepository): Response
    {
        return $this->render('admin/commande_trimestrielle/index.html.twig', [
            'commande_trimestrielles' => $commandeTrimestrielleRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_commande_trimestrielle_liste', methods: ['GET', 'POST'])]
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
        
        $commandes = $commandeService->getAllCommandeTrimestrielle($entityManager, (int) $start, (int) $length, $recherche);
      
        $total = $commandeService->getNombreTotalCommandeTrimestrielle($entityManager);
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
        $Infocommande['isActive'] = (($commande['isActive'] && $commande['isActive'] != null) ? "Actif" : "Inactif");
        $Infocommande['Slug'] = $commande['Slug'];
        $Infocommande['id'] = $commande['id'];
        if (!in_array($Infocommande, $tabcommandes["data"])) {
            array_push($tabcommandes["data"], $Infocommande);
        }
        }
    
        return new JsonResponse($tabcommandes);
    }

    #[Route('/new', name: 'app_commande_trimestrielle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commandeTrimestrielle = new CommandeTrimestrielle();
        $form = $this->createForm(CommandeTrimestrielleType::class, $commandeTrimestrielle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commandeTrimestrielle);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_trimestrielle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/commande_trimestrielle/new.html.twig', [
            'commande_trimestrielle' => $commandeTrimestrielle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_trimestrielle_show', methods: ['GET'])]
    public function show(CommandeTrimestrielle $commandeTrimestrielle): Response
    {
        return $this->render('admin/commande_trimestrielle/show.html.twig', [
            'commande_trimestrielle' => $commandeTrimestrielle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_trimestrielle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommandeTrimestrielle $commandeTrimestrielle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeTrimestrielleType::class, $commandeTrimestrielle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_trimestrielle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/commande_trimestrielle/edit.html.twig', [
            'commande_trimestrielle' => $commandeTrimestrielle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_trimestrielle_delete', methods: ['POST'])]
    public function delete(Request $request, CommandeTrimestrielle $commandeTrimestrielle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandeTrimestrielle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commandeTrimestrielle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_trimestrielle_index', [], Response::HTTP_SEE_OTHER);
    }
}
