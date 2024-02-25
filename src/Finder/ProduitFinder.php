<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class ProduitFinder
{
    private $em; 

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em;
    } 

    public function findAllProduct()
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('p.id AS IdProduit, 
                p.Nom AS NomProduit,
                cr.id AS IdCentreRecuperation,
                cr.Nom AS NomCentreRecuperation')
            ->from('App:Produit', 'p')
            ->join('p.Type', 'cr') 
            ->orderBy('p.Nom', 'ASC') ;
        $resultAnneePrevisionnelle = $queryBuilder->getQuery()->getArrayResult(); 
        return $resultAnneePrevisionnelle;
    }
}

?>