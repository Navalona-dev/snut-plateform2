<?php

namespace App\Finder;

use Doctrine\ORM\EntityManagerInterface;

class UserFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findDataUser($prmId)
    {
        if (empty($prmId)) {
            return null;
        }
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('u.id AS idUser, u.Nom AS nomUser, u.Prenoms AS prenomsUser, u.email AS emailUser, u.Telephone AS telephone,
            p.id as provinceId, p.NomFR AS nomFRProvince, p.NomMG AS nomMGProvince, r.id AS idRegion, r.Nom AS nomRegion, 
            d.id AS idDistrict, d.Nom AS nomDistrict, d.isEligibleForCreni AS isEligibleForCreni, d.isEligibleForCrenas AS isEligibleForCrenas,
            c.id AS idCHU, c.Nom AS nomCHU')
            ->from('App:User', 'u')
            ->leftJoin('u.Province', 'p')
            ->leftJoin('u.Region', 'r')
            ->leftJoin('u.District', 'd')
            ->leftJoin('u.CentreHospitalierUniversitaire', "c")
            ->where('u.id = :id')
            ->setParameter('id', $prmId);
        $resultDataUser = $queryBuilder->getQuery()->getArrayResult()[0];
        return $resultDataUser;
    }

    public function findAllUsersByRole($role)
    {
        if (empty($role)) {
            return null;
        }
        // Récupérer tous les utilisateurs
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('u.id AS idUser, u.Nom AS nomUser, u.Prenoms AS prenomsUser, u.email AS emailUser, u.Telephone AS telephone, u.roles, 
                        p.id as provinceId, p.NomFR AS nomFRProvince, p.NomMG AS nomMGProvince, r.id AS idRegion, r.Nom AS nomRegion, 
                        d.id AS idDistrict, d.Nom AS nomDistrict, d.isEligibleForCreni AS isEligibleForCreni, d.isEligibleForCrenas AS isEligibleForCrenas,
                        c.id AS idCHU, c.Nom AS nomCHU')
            ->from('App:User', 'u')
            ->leftJoin('u.Province', 'p')
            ->leftJoin('u.Region', 'r')
            ->leftJoin('u.District', 'd')
            ->leftJoin('u.CentreHospitalierUniversitaire', 'c');

        $allUsers = $queryBuilder->getQuery()->getArrayResult();

        // Filtrer les utilisateurs par le rôle
        $filteredUsers = array_filter($allUsers, function ($user) use ($role) {
            return in_array($role, $user['roles']);
        });

        return $filteredUsers;
    }

    public function findAllCentralSupervisors()
    {
        return $this->findAllUsersByRole('ROLE_CENTRAL_SUPERVISOR');
    }

    public function findAllRegionalSupervisors()
    {
        return $this->findAllUsersByRole('ROLE_REGIONAL_SUPERVISOR');
    }

    public function findAllDistrictAgents()
    {
        return $this->findAllUsersByRole('ROLE_AGENT_DISTRICT');
    }
}
