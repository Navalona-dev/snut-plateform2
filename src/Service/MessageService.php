<?php

namespace App\Service;

use App\Entity\Message;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\QuantitiesProductRiskExpiry;
use App\Entity\StockData;
use App\Finder\MessageFinder;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class MessageService
{

    private $_messageFinder;
    private $_messageRepository;

    public function __construct(EntityManagerInterface $em, ManagerRegistry $registry)
    {
        $this->_messageFinder = new MessageFinder($em);
        $this->_messageRepository = new MessageRepository($registry);
    }

    // Fonction pour persiter dans la table stock_data
    public function persistMessage(EntityManagerInterface $entityManager, $prmMessage)
    {
        $message = new Message();
        $message->setSender($prmMessage['sender']);
        $message->setRecipient($prmMessage['recipient']);
        $message->setTextMessage($prmMessage['textMessage']);
        $message->setIsRead(0);
        $message->setDate(new \DateTime());

        try {
            $entityManager->persist($message);
            $entityManager->flush();
        } catch (\Throwable $th) {
            throw $th;
        }
        return $message;
    }
}