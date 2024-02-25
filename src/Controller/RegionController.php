<?php

namespace App\Controller;

use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegionController extends AbstractController
{
    #[Route('/region', name: 'app_region')]
    public function index(): Response
    {
        return $this->render('location/region.html.twig', [
            'controller_name' => 'RegionController',
        ]);
    }

    #[Route('/regionProvince', name: 'app_region_province')]
    public function getRegionByProvince(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        $provinceId = $request->get('province_id'); // Récupérez l'ID de la province sélectionnée depuis la requête AJAX
 
        // Utilisez cet ID pour extraire les régions correspondantes de la base de données
        $regions = $entityManager->getRepository(Region::class)->findBy(['province' => $provinceId]);
        
        // Transformez les données en un tableau JSON
        $data = [];
        foreach ($regions as $region) {
            $data[] = [
                'id' => $region->getId(),
                'name' => $region->getNom(),
            ];
        }

        return new JsonResponse($data);
    }
}
