<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class CommandeTrimestrielleFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em;
    } 

    public function findDataCommandeTrimestrielle()
    {  
        $query = $this->em->createQuery(
            'SELECT c.id AS idCommandeTrimestrielle, 
                    c.isActive AS isActive,
                    c.Nom AS nomCommande, 
                    c.DateDebut AS dateDebutCommande,
                    c.DateFin AS dateFinCommande,
                    c.Slug AS Slug,
                    a.id AS idAnneePrevisionnelle,
                    a.Annee AS AnneePrevisionnelle
             FROM App:CommandeTrimestrielle c
             INNER JOIN App:AnneePrevisionnelle a WITH c.AnneePrevisionnelle = a.id
             WHERE c.isActive = 1
             ORDER BY c.id DESC
        ')
        ->setMaxResults(1);
        $resultDataCommandeTrimestrielle = $query->getOneOrNullResult(); 
        return $resultDataCommandeTrimestrielle;
    }
}

?>