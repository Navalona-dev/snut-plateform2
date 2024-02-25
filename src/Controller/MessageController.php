<?php

namespace App\Controller;

use App\Entity\Message;
use App\Finder\UserFinder;
use App\Finder\MessageFinder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private $_userService;
    private $_messageService;
    private $_entityManager;

    public function __construct(UserFinder $user_service_container, MessageFinder $message_container, EntityManagerInterface $entityManager)
    {
        $this->_userService = $user_service_container;
        $this->_messageService = $message_container;
        $this->_entityManager = $entityManager;
    }

    #[Route('/retro_information', name: 'app_retroinformation')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $userId = $user->getId();
            $dataUser = $this->_userService->findDataUser($userId);

            $AllCentralSupervisor = $this->_userService->findAllCentralSupervisors();
            $AllRegionalSupervisors = $this->_userService->findAllRegionalSupervisors();
            $AllDistrictAgents = $this->_userService->findAllDistrictAgents();

            $sentMessages = $this->_messageService->findAllMessagebySender($user);
            $receivedMessages = $this->_messageService->findAllMessagebyRecipient($user);
            $unreadMessageCount = $this->_entityManager->getRepository(Message::class)->count([
                'recipient' => $user,
                'isRead' => false,
                'isDeletedPerRecipient' => null,
            ]);
            return $this->render('message/homeMessage.html.twig', [
                'controller_name' => 'PvrdController',
                "dataUser" => $dataUser,
                'allCentralSupervisor' => $AllCentralSupervisor,
                'allRegionalSupervisors' => $AllRegionalSupervisors,
                'allDistrictAgents' => $AllDistrictAgents,
                'sentMessages' => $sentMessages,
                'receivedMessages' => $receivedMessages,
                'unreadMessageCount' => $unreadMessageCount
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
