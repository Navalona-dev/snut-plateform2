<?php

namespace App\Controller;

use App\Entity\DataCrenas;
use App\Form\DataCrenasType;
use App\Repository\DataCrenasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/data/crenas')]
class DataCrenasController extends AbstractController
{
    #[Route('/', name: 'app_data_crenas_index', methods: ['GET'])]
    public function index(DataCrenasRepository $dataCrenasRepository): Response
    {
        return $this->render('data_crenas/index.html.twig', [
            'data_crenas' => $dataCrenasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_data_crenas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dataCrena = new DataCrenas();
        $form = $this->createForm(DataCrenasType::class, $dataCrena);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dataCrena);
            $entityManager->flush();

            return $this->redirectToRoute('app_data_crenas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('data_crenas/new.html.twig', [
            'data_crena' => $dataCrena,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_crenas_show', methods: ['GET'])]
    public function show(DataCrenas $dataCrena): Response
    {
        return $this->render('data_crenas/show.html.twig', [
            'data_crena' => $dataCrena,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_data_crenas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DataCrenas $dataCrena, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DataCrenasType::class, $dataCrena);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_data_crenas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('data_crenas/edit.html.twig', [
            'data_crena' => $dataCrena,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_crenas_delete', methods: ['POST'])]
    public function delete(Request $request, DataCrenas $dataCrena, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dataCrena->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dataCrena);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_data_crenas_index', [], Response::HTTP_SEE_OTHER);
    }
}
