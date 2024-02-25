<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class AnneePrevisionelleFinder
{
    private $em; 

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em;
    } 

    public function findDataAnneePrevisionnelle()
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('a.id AS IdAnneePrevisionnelle, 
                a.Annee AS Annee, 
                a.ValeurCalculTheoriqueATPE01 AS ValeurCalculTheoriqueATPE01,
                a.ValeurCalculTheoriqueAMOX01 AS ValeurCalculTheoriqueAMOX01,
                a.ValeurCalculTheoriqueFichePatient01 AS ValeurCalculTheoriqueFichePatient01,
                a.ValeurCalculTheoriqueRegistre01 AS ValeurCalculTheoriqueRegistre01,
                a.ValeurCalculTheoriqueCarnetRapport01 AS ValeurCalculTheoriqueCarnetRapport01,
                a.ValeurCalculTheoriqueATPE02 AS ValeurCalculTheoriqueATPE02,
                a.ValeurCalculTheoriqueAMOX02 AS ValeurCalculTheoriqueAMOX02,
                a.ValeurCalculTheoriqueFichePatient02 AS ValeurCalculTheoriqueFichePatient02,
                a.ValeurCalculTheoriqueRegistre02 AS ValeurCalculTheoriqueRegistre02,
                a.ValeurCalculTheoriqueCarnetRapport02 AS ValeurCalculTheoriqueCarnetRapport02')
            ->from('App:AnneePrevisionnelle', 'a')
            ->orderBy('a.Annee', 'DESC')
            ->setMaxResults(1);
        $resultAnneePrevisionnelle = $queryBuilder->getQuery()->getArrayResult()[0]; 
        return $resultAnneePrevisionnelle;
    }
}

?>