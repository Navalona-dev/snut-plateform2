<?php
namespace App\Service;

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

class UserService
{
    private $tokenStorage;
    private $authorization;
    private $entityManagerMatac;
    private $entityManagerFtm;
    private $session;
    public  $isCurrentDossier = false;

    public function __construct(AuthorizationManager $authorization, TokenStorageInterface  $TokenStorageInterface, EntityManager $entityManagerMatac, EntityManager $entityManagerFtm, SessionInterface $session)
    {
        $this->tokenStorage = $TokenStorageInterface;
        $this->authorization = $authorization;
        /*$this->entityManagerMatac = $managerRegistry->getManager("matac");
        $this->entityManagerFtm = $managerRegistry->getManager("ftm");*/
        $this->entityManagerMatac = $entityManagerMatac;
        $this->entityManagerFtm = $entityManagerFtm;
        $this->session = $session;
    }

    public function add($user)
    {
        $user->setIsActive(false);
        $user->setIsClient(false);
        if ($user->getPrivileges()) {
            // Assignation privilege au utilisateur
            foreach ($user->getPrivileges() as $key => $privilege) {
                $privilege->addUser($user);
            }
        }

        $this->entityManagerMatac->persist($user);
        return $user;
    }

    public function findUserByEmail($email) {
        if (!is_null($email) && $email != "") {
            $user = $this->entityManagerMatac->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                return $user;
            }
        }
        return false;
    }

    public function findUserByUsername($username) {
        if (!is_null($username) && $username != "") {
            $user = $this->entityManagerMatac->getRepository(User::class)->findOneBy(['username' => $username]);
            if ($user) {
                return $user;
            }
        }
        return false;
    }

    public function getAllPrivilegeUser($idUser)
    {
        return $this->entityManagerMatac->getRepository(User::class)->getAllPrivilegeUser($idUser);
    }
    public function deletePrivilegeUser($privilegeId, $idUser)
    {
        return $this->entityManagerMatac->getRepository(User::class)->deletePrivilegeUser($privilegeId, $idUser);
    }

    public function insertPrivilegeUser($privilegeId, $idUser)
    {
        return $this->entityManagerMatac->getRepository(User::class)->insertPrivilegeUser($privilegeId, $idUser);
    }

    public function persist($user)
    {
        $this->entityManagerMatac->persist($user);
    }

    public function setResetToken($user, $token)
    {
        $this->entityManagerMatac->getRepository(User::class)->setResetToken($user, $token);
    }

    public function setActivationNullToken($user)
    {
        $this->entityManagerMatac->getRepository(User::class)->setActivationNullToken($user);
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
        $this->entityManagerMatac->flush();
    }

    public function removeUser($user)
    {

    }

    public function getAllUser($start = 0, $limit = 0, $search = "")
    {
        return $this->entityManagerMatac->getRepository(User::class)->getAllUser($start, $limit, $search);
    }

    public function getNombreTotalUser()
    {
        return $this->entityManagerMatac->getRepository(User::class)->countAll();
    }
}