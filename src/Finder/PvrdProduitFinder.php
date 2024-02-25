<?php

namespace App\Finder;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;

Class PvrdProduitFinder
{
    private $em; 

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em;
    }

    public function findDataPvrdProduitByIdPvrd($prmPvrdId)
    {
        $query = $this->em->createQuery("SELECT 
                                            pvp.id AS id,
                                            pvp.QuantiteInscritSurBL AS QuantiteInscritSurBL,
                                            pvp.QuantiteRecue AS QuantiteRecue,
                                            pvp.EcartEntreQuantite AS EcartEntreQuantite,
                                            pvp.Periode AS Periode,
                                            p.id AS ProduitId,
                                            p.Nom AS ProduitNom
                                        FROM App:PvrdProduit pvp 
                                        INNER JOIN App:Produit p WITH pvp.Produit = p.id
                                        WHERE pvp.Pvrd = :prmPvrdId")
                    ->setParameter('prmPvrdId', $prmPvrdId);

        try {
            $resultLstPvrdProduit = $query->getArrayResult();  
        } catch (\Exception $e) {
            // Gérer l'erreur
            // Par exemple, afficher le message d'erreur pour le débogage
            echo $e->getMessage();
            // Peut-être retourner une valeur par défaut ou autre chose en cas d'erreur
            return [];
        }
        
        return $resultLstPvrdProduit;
    } 
}