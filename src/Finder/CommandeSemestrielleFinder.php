<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class CommandeSemestrielleFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em;
    } 

    public function findDataCommandeSemestrielle()
    {  
        $query = $this->em->createQuery(
            'SELECT c.id AS idCommandeSemestrielle, 
                    c.IsActive AS isActive,
                    c.Nom AS nomCommande, 
                    c.DateDebut AS dateDebutCommande,
                    c.DateFin AS dateFinCommande,
                    c.Slug AS Slug,
                    a.id AS idAnneePrevisionnelle,
                    a.Annee AS AnneePrevisionnelle
             FROM App:CommandeSemestrielle c
             INNER JOIN App:AnneePrevisionnelle a WITH c.AnneePrevisionnelle = a.id
             WHERE c.IsActive = 1
             ORDER BY c.id DESC
        ')
        ->setMaxResults(1);
        $resultDataCommandeSemestrielle = $query->getOneOrNullResult(); 
        return $resultDataCommandeSemestrielle;
    }
}

?>