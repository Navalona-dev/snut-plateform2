<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`message`')]
#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name: 'sender_id')]
    private ?User $sender = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name: 'recipient_id')]
    private ?User $recipient = null;

    #[ORM\Column(name: 'is_read')]
    private ?bool $isRead = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true, name: 'text_message')]
    private ?string $textMessage = null;

    #[ORM\Column(nullable: true, name: 'is_deleted_per_sender')]
    private ?bool $isDeletedPerSender = null;

    #[ORM\Column(nullable: true, name: 'is_deleted_per_recipient')]
    private ?bool $isDeletedPerRecipient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTextMessage(): ?string
    {
        return $this->textMessage;
    }

    public function setTextMessage(?string $textMessage): static
    {
        $this->textMessage = $textMessage;

        return $this;
    }

    public function isIsDeletedPerSender(): ?bool
    {
        return $this->isDeletedPerSender;
    }

    public function setIsDeletedPerSender(?bool $isDeletedPerSender): static
    {
        $this->isDeletedPerSender = $isDeletedPerSender;

        return $this;
    }

    public function isIsDeletedPerRecipient(): ?bool
    {
        return $this->isDeletedPerRecipient;
    }

    public function setIsDeletedPerRecipient(?bool $isDeletedPerRecipient): static
    {
        $this->isDeletedPerRecipient = $isDeletedPerRecipient;

        return $this;
    }
}
