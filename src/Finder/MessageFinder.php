<?php

namespace App\Finder;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\OrderBy;

class MessageFinder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findAllMessagebySender($prmSender)
    {
        $query = $this->em->createQuery("SELECT 
                m.id AS id,
                us.id AS idSender,
                us.email AS emailSender,
                us.Nom AS nomSender,
                us.Prenoms AS prenomsSender,
                ur.id AS idRecipient,
                ur.email AS emailRecipient,
                ur.Nom AS nomRecipient,
                ur.Prenoms AS prenomsRecipient,
                m.isRead AS isRead,
                m.date AS date,
                m.textMessage AS textMessage,
                m.isDeletedPerSender AS isDeletedPerSender,
                m.isDeletedPerRecipient AS isDeletedPerRecipient,
                pj.id AS pjId,
                pj.fileName AS fileName
            FROM App:Message m
            LEFT JOIN  App:User us WITH m.sender = us.id
            LEFT JOIN  App:User ur WITH m.recipient = ur.id
            LEFT JOIN  App:PieceJointe pj WITH pj.message = m.id
            WHERE m.sender = :sender")
            ->setParameter('sender', $prmSender);

        $resultMessage = $query->getResult();
        
        // Traiter les doublons
        $messages = [];
        foreach ($resultMessage as $message) {
            $id = $message['id'];
            if (!isset($messages[$id])) {
                // Première occurrence du message, initialisez les pièces jointes
                $messages[$id] = $message;
                $messages[$id]['pieceJointes'] = [];
            }

            // Ajoutez la pièce jointe au tableau des pièces jointes
            if ($message['pjId'] !== null) {
                $messages[$id]['pieceJointes'][] = [
                    'pjId' => $message['pjId'],
                    'fileName' => $message['fileName'],
                ];
            }
        }

        return array_values($messages);
    }

    public function findAllMessagebyRecipient($prmRecipient)
    {

        $query = $this->em->createQuery("SELECT 
                m.id AS id,
                us.id AS idSender,
                us.email AS emailSender,
                us.Nom AS nomSender,
                us.Prenoms AS prenomsSender,
                ur.id AS idRecipient,
                ur.email AS emailRecipient,
                ur.Nom AS nomRecipient,
                ur.Prenoms AS prenomsRecipient,
                m.isRead AS isRead,
                m.date AS date,
                m.textMessage AS textMessage,
                m.isDeletedPerSender AS isDeletedPerSender,
                m.isDeletedPerRecipient AS isDeletedPerRecipient,
                pj.id AS pjId,
                pj.fileName AS fileName
            FROM App:Message m
            LEFT JOIN  App:User us WITH m.sender = us.id
            LEFT JOIN  App:User ur WITH m.recipient = ur.id
            LEFT JOIN  App:PieceJointe pj WITH pj.message = m.id
            WHERE m.recipient = :recipient")
            ->setParameter('recipient', $prmRecipient);

        $resultMessage = $query->getResult();
        
        // Traiter les doublons
        $messages = [];
        foreach ($resultMessage as $message) {
            $id = $message['id'];
            if (!isset($messages[$id])) {
                // Première occurrence du message, initialisez les pièces jointes
                $messages[$id] = $message;
                $messages[$id]['pieceJointes'] = [];
            }

            // Ajoutez la pièce jointe au tableau des pièces jointes
            if ($message['pjId'] !== null) {
                $messages[$id]['pieceJointes'][] = [
                    'pjId' => $message['pjId'],
                    'fileName' => $message['fileName'],
                ];
            }
        }

        return array_values($messages);
    }

    // public function findStockDataCountByStatus()
    // {

    //     $query = $this->em->createQuery('
    //         SELECT 
    //             r.id AS regionId,
    //             r.Nom AS regionNom,
    //             d.id AS districtId,
    //             d.Nom AS districtNom,
    //             COUNT(sd.id) AS totalStockData,
    //             SUM(sd.nombreCSB) AS totalNombreCSB,
    //             SUM(sd.nombreRupture) AS totalNombreRupture,
    //             SUM(sd.nombreSoustock) AS totalNombreSoustock,
    //             SUM(sd.nombreNormal) AS totalNombreNormal,
    //             SUM(sd.nombreSurStock) AS totalNombreSurStock
    //         FROM App:District d  
    //         LEFT JOIN d.region r
    //         LEFT JOIN App:RmaNut rn WITH rn.District = d.id
    //         LEFT JOIN App:StockData sd WITH sd.rmaNut = rn.id
    //         GROUP BY r.id, r.Nom, d.id, d.Nom
    //     ');

    //     return $query->getArrayResult();
    // }
}
