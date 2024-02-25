<?php
namespace App\Service;

use App\Entity\District;
use App\Service\AuthorizationManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Exception\PropertyVideException;
use App\Exception\ActionInvalideException;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DistrictService
{
    private $tokenStorage;
    private $authorization;
    private $entityManagerSnut;
    private $entityManagerFtm;
    private $session;
    public  $isCurrentDossier = false;

    public function __construct()
    {

    }
    
    /*public function add($user)
    {
        $user->setIsActive(false);
        $user->setIsClient(false);
        if ($user->getPrivileges()) {
            // Assignation privilege au utilisateur
            foreach ($user->getPrivileges() as $key => $privilege) {
                $privilege->addUser($user);
            }
        }

        $this->entityManagerSnut->persist($user);
        return $user;
    }

    public function findUserByEmail($email) {
        if (!is_null($email) && $email != "") {
            $user = $this->entityManagerSnut->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                return $user;
            }
        }
        return false;
    }

    public function findUserByUsername($username) {
        if (!is_null($username) && $username != "") {
            $user = $this->entityManagerSnut->getRepository(User::class)->findOneBy(['username' => $username]);
            if ($user) {
                return $user;
            }
        }
        return false;
    }

    public function getAllPrivilegeUser($idUser)
    {
        return $this->entityManagerSnut->getRepository(User::class)->getAllPrivilegeUser($idUser);
    }
    public function deletePrivilegeUser($privilegeId, $idUser)
    {
        return $this->entityManagerSnut->getRepository(User::class)->deletePrivilegeUser($privilegeId, $idUser);
    }

    public function insertPrivilegeUser($privilegeId, $idUser)
    {
        return $this->entityManagerSnut->getRepository(User::class)->insertPrivilegeUser($privilegeId, $idUser);
    }

    public function persist($user)
    {
        $this->entityManagerSnut->persist($user);
    }

    public function setResetToken($user, $token)
    {
        $this->entityManagerSnut->getRepository(User::class)->setResetToken($user, $token);
    }

    public function setActivationNullToken($user)
    {
        $this->entityManagerSnut->getRepository(User::class)->setActivationNullToken($user);
    }

    public function setUser($user)
    {

        $privilegeUser = $this->getAllPrivilegeUser($user->getId());
        if (count($user->getPrivileges()) > 0) {

            $tabIdPrivileges = [];
            // Assignation privilege au utilisateur
            foreach ($user->getPrivileges() as $key => $privilege) {
                if (!in_array($privilege->getId(), $tabIdPrivileges)) {
                    array_push($tabIdPrivileges, $privilege->getId());
                }
                $privilege->addUser($user);
            }

            if ($privilegeUser != false) {
                foreach($privilegeUser as $privilegeUserDelete) {
                    if (!in_array($privilegeUserDelete, $tabIdPrivileges)) {
                        $this->deletePrivilegeUser($privilegeUserDelete, $user->getId());
                    }
                }
            }

            if (sizeof($tabIdPrivileges) > 0) {
                if ($privilegeUser != false) {
                    foreach($tabIdPrivileges as $privilegeInsert) {
                        if (!in_array($privilegeInsert, $privilegeUser)) {
                            $this->insertPrivilegeUser($privilegeInsert, $user->getId());
                        }
                    }
                } else {
                    foreach($tabIdPrivileges as $privilegeInsert) {
                            $this->insertPrivilegeUser($privilegeInsert, $user->getId());
                    }
                }

            }
        } else {
            if ($privilegeUser != false) {
                // Suppression
                foreach($privilegeUser as $privilegeDelete) {
                    $this->deletePrivilegeUser($privilegeDelete, $user->getId());
                }
            }
        }
    }

    public function update()
    {
        $this->entityManagerSnut->flush();
    }

    public function removeUser($user)
    {

    }*/

    public function getAllDistrict($em, $start = 0, $limit = 0, $search = "")
    {
        return $em->getRepository(District::class)->getAllDistrict($start, $limit, $search);
    }

    public function getNombreTotalDistrict($em)
    {
        return $em->getRepository(District::class)->countAll();
    }
}