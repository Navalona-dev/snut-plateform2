<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class CreniMoisProjectionAdmissionFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    } 

    public function findDataMoisProjection($prmCommandeSemestrielleId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('cm.id AS idCreniMoisProjection, 
                cm.Mois01AdmissionCreniPrecedent AS Mois01AdmissionCreniPrecedent, 
                cm.Mois02AdmissionCreniPrecedent AS Mois02AdmissionCreniPrecedent, 
                cm.Mois03AdmissionCreniPrecedent AS Mois03AdmissionCreniPrecedent, 
                cm.Mois04AdmissionCreniPrecedent AS Mois04AdmissionCreniPrecedent, 
                cm.Mois05AdmissionCreniPrecedent AS Mois05AdmissionCreniPrecedent, 
                cm.Mois06AdmissionCreniPrecedent AS Mois06AdmissionCreniPrecedent, 
                cm.Mois01AdmissionProjeteAnneePrecedent AS Mois01AdmissionProjeteAnneePrecedent,
                cm.Mois02AdmissionProjeteAnneePrecedent AS Mois02AdmissionProjeteAnneePrecedent,
                cm.Mois03AdmissionProjeteAnneePrecedent AS Mois03AdmissionProjeteAnneePrecedent,
                cm.Mois04AdmissionProjeteAnneePrecedent AS Mois04AdmissionProjeteAnneePrecedent,
                cm.Mois05AdmissionProjeteAnneePrecedent AS Mois05AdmissionProjeteAnneePrecedent,
                cm.Mois06AdmissionProjeteAnneePrecedent AS Mois06AdmissionProjeteAnneePrecedent,
                cm.Mois01ProjectionAnneePrevisionnelle AS Mois01ProjectionAnneePrevisionnelle,
                cm.Mois02ProjectionAnneePrevisionnelle AS Mois02ProjectionAnneePrevisionnelle,
                cm.Mois03ProjectionAnneePrevisionnelle AS Mois03ProjectionAnneePrevisionnelle,
                cm.Mois04ProjectionAnneePrevisionnelle AS Mois04ProjectionAnneePrevisionnelle,
                cm.Mois05ProjectionAnneePrevisionnelle AS Mois05ProjectionAnneePrevisionnelle,
                cm.Mois06ProjectionAnneePrevisionnelle AS Mois06ProjectionAnneePrevisionnelle
            ')
            ->from('App:CreniMoisProjectionsAdmissions', 'cm') 
            ->join('cm.CommandeSemestrielle', 'cs') 
            ->where('cs.id = :commandeSemestrielle')
            ->setParameter('commandeSemestrielle', $prmCommandeSemestrielleId);  
        $result = $queryBuilder->getQuery()->getArrayResult(); 
        $resultDataMoisProjection = null;
        if (is_array($result) && count($result) > 0) {
            $resultDataMoisProjection = $result[0];
        } 
        return $resultDataMoisProjection;
    }
}

?>