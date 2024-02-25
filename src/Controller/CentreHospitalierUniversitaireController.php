<?php

namespace App\Controller;

use App\Entity\CentreHospitalierUniversitaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CentreHospitalierUniversitaireController extends AbstractController
{
    #[Route('/centre/hospitalier/universitaire', name: 'app_centre_hospitalier_universitaire')]
    public function index(): Response
    {
        return $this->render('centre_hospitalier_universitaire/index.html.twig', [
            'controller_name' => 'CentreHospitalierUniversitaireController',
        ]);
    }

    #[Route('/chuDistrict', name: 'app_chu_district')]
    public function getRegionByProvince(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $districtId = $request->get('district_id'); // Récupérez l'ID de la région sélectionnée depuis la requête AJAX

        // Utilisez cet ID pour extraire les régions correspondantes de la base de données
        $CentreHospitalierUniversitaires = $entityManager->getRepository(CentreHospitalierUniversitaire::class)->findBy(['District' => $districtId]);
        // Transformez les données en un tableau JSON
        $data = [];
        foreach ($CentreHospitalierUniversitaires as $chu) {
            $data[] = [
                'id' => $chu->getId(),
                'name' => $chu->getNom(),
            ];
        } 
        return new JsonResponse($data);
    }
}
