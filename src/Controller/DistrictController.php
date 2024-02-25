<?php

namespace App\Controller;

use App\Entity\District;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DistrictController extends AbstractController
{
    #[Route('/district', name: 'app_district')]
    public function index(): Response
    {
        return $this->render('district/index.html.twig', [
            'controller_name' => 'DistrictController',
        ]);
    }

    #[Route('/districtRegion', name: 'app_district_region')]
    public function getRegionByProvince(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $regionId = $request->get('region_id'); // Récupérez l'ID de la région sélectionnée depuis la requête AJAX

        // Utilisez cet ID pour extraire les régions correspondantes de la base de données
        $districts = $entityManager->getRepository(District::class)->findBy(['region' => $regionId]);
        // Transformez les données en un tableau JSON
        $data = [];
        foreach ($districts as $district) {
            $data[] = [
                'id' => $district->getId(),
                'name' => $district->getNom(),
            ];
        } 
        return new JsonResponse($data);
    }
}
