<?php

namespace App\Controller\Admin2;

use App\Entity\MoisPrevisionnelleEnclave;
use App\Form\MoisPrevisionnelleEnclaveType;
use App\Repository\GroupeRepository;
use App\Repository\MoisPrevisionnelleEnclaveRepository;
use App\Service\MoisPrevionnelleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/mois/previsionnelle/enclave')]
class AdminMoisPrevisionnelleEnclaveController extends AbstractController
{
    #[Route('/', name: 'app_mois_previsionnelle_enclave_index', methods: ['GET'])]
    public function index(MoisPrevisionnelleEnclaveRepository $moisPrevisionnelleEnclaveRepository): Response
    {
        return $this->render('admin/mois_previsionnelle_enclave/index.html.twig', [
            'mois_previsionnelle_enclaves' => $moisPrevisionnelleEnclaveRepository->findAll(),
        ]);
    }

    #[Route('/ajax', name: 'app_admin_mois_pervision_liste', methods: ['GET', 'POST'])]
    public function liste(Request $request, MoisPrevionnelleService $moisPrevionnelleService, EntityManagerInterface $entityManager, GroupeRepository $groupeRepository): Response
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
        
        $moisPrevisionnelles = $moisPrevionnelleService->getAllMois($entityManager, (int) $start, (int) $length, $recherche);
            
        $total = $moisPrevionnelleService->getNombreTotalMois($entityManager);
        $recordsTotal = (($total > 0) ? $total : 0);
        $tabmoisPrevisionnelles = [];
        $tabmoisPrevisionnelles["draw"] = $draw;
        $tabmoisPrevisionnelles["recordsTotal"] = $recordsTotal;
        $tabmoisPrevisionnelles["recordsFiltered"] = $recordsTotal;
        $tabmoisPrevisionnelles["data"] = [];
        
        foreach ($moisPrevisionnelles as $key => $moisPrevisionnelle) {
        $InfomoisPrevisionnelle = [];
        $InfomoisPrevisionnelle['mois'] = $moisPrevisionnelle['mois'];
        $groupe = $groupeRepository->find($moisPrevisionnelle['groupe_id']);
        
         $InfomoisPrevisionnelle['groupe'] = $groupe->getNom().'-'.$groupe->getAnnee()->getAnnee();
        /*$InfomoisPrevisionnelle['email'] = $moisPrevisionnelle['email'];*/
        $InfomoisPrevisionnelle['id'] = $moisPrevisionnelle['id'];
        if (!in_array($InfomoisPrevisionnelle, $tabmoisPrevisionnelles["data"])) {
            array_push($tabmoisPrevisionnelles["data"], $InfomoisPrevisionnelle);
        }
        }
    
        return new JsonResponse($tabmoisPrevisionnelles);
    }

    #[Route('/new', name: 'app_mois_previsionnelle_enclave_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $moisPrevisionnelleEnclave = new MoisPrevisionnelleEnclave();
        $form = $this->createForm(MoisPrevisionnelleEnclaveType::class, $moisPrevisionnelleEnclave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($moisPrevisionnelleEnclave);
            $entityManager->flush();

            return $this->redirectToRoute('app_mois_previsionnelle_enclave_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/mois_previsionnelle_enclave/new.html.twig', [
            'mois_previsionnelle_enclave' => $moisPrevisionnelleEnclave,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mois_previsionnelle_enclave_show', methods: ['GET'])]
    public function show(MoisPrevisionnelleEnclave $moisPrevisionnelleEnclave): Response
    {
        return $this->render('admin/mois_previsionnelle_enclave/show.html.twig', [
            'mois_previsionnelle_enclave' => $moisPrevisionnelleEnclave,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mois_previsionnelle_enclave_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MoisPrevisionnelleEnclave $moisPrevisionnelleEnclave, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MoisPrevisionnelleEnclaveType::class, $moisPrevisionnelleEnclave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mois_previsionnelle_enclave_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/mois_previsionnelle_enclave/edit.html.twig', [
            'mois_previsionnelle_enclave' => $moisPrevisionnelleEnclave,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mois_previsionnelle_enclave_delete', methods: ['POST'])]
    public function delete(Request $request, MoisPrevisionnelleEnclave $moisPrevisionnelleEnclave, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moisPrevisionnelleEnclave->getId(), $request->request->get('_token'))) {
            $entityManager->remove($moisPrevisionnelleEnclave);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mois_previsionnelle_enclave_index', [], Response::HTTP_SEE_OTHER);
    }
}
