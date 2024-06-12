<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class MoisProjectionAdmissionFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em; 
    } 

    public function findGroupeByCommande($prmCommandeTrimestrielleId = null)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('m.id AS idMoisProjection, 
                g.id AS idGroupe,
                g.Nom AS nomGroupe,
                c.id AS idCommandeTrimestrielle,
                GROUP_CONCAT(d.id) as districts
                ')
            ->from('App:MoisProjectionsAdmissions', 'm')
            ->join('m.Groupe', 'g')
            ->join('g.districts', 'd')
            ->join('m.CommandeTrimestrielle', 'c')
            ->where('c.id = :commandeTrimestrielle') 
            ->setParameter('commandeTrimestrielle', $prmCommandeTrimestrielleId)
            ->groupBy('g.id');
        $resultDataMoisProjection = $queryBuilder->getQuery()->getArrayResult(); 
        return $resultDataMoisProjection;
    }

    public function findDataMoisProjection($prmGroupeId, $prmCommandeTrimestrielleId)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('m.id AS idMoisProjection, 
                m.MoisAdmissionCRENASAnneePrecedent AS MoisAdmissionCRENASAnneePrecedent, 
                m.MoisAdmissionProjeteAnneePrecedent AS MoisAdmissionProjeteAnneePrecedent,
                m.MoisProjectionAnneePrevisionnelle AS MoisProjectionAnneePrevisionnelle')
            ->from('App:MoisProjectionsAdmissions', 'm')
            ->join('m.Groupe', 'g')
            ->join('m.CommandeTrimestrielle', 'c')
            ->where('g.id = :groupeId') 
            ->andWhere('c.id = :commandeTrimestrielle') 
            ->setParameter('groupeId', $prmGroupeId)
            ->setParameter('commandeTrimestrielle', $prmCommandeTrimestrielleId);  
        $resultDataMoisProjection = $queryBuilder->getQuery()->getArrayResult(); 
        return $resultDataMoisProjection;
    }

    public function findDataMoisPrevisionnelleProjection($prmGroupeId, $prmCommandeTrimestrielleId = null)
    { 
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder 
            ->select('m.id AS idMoisProjection, 
                m.mois AS MoisProjectionAnneePrevisionnelle')
            ->from('App:MoisPrevisionnelleEnclave', 'm')
            ->join('m.groupe', 'g')
            ->where('g.id = :groupeId') 
            ->setParameter('groupeId', $prmGroupeId);
        $resultDataMoisPrevisionneleProjection = $queryBuilder->getQuery()->getArrayResult(); 
        return $resultDataMoisPrevisionneleProjection;
    }
}

?>