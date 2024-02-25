<?php

namespace App\Entity;

use App\Repository\PieceJointeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`piece_jointe`')]
#[ORM\Entity(repositoryClass: PieceJointeRepository::class)]
class PieceJointe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, name: 'message_id')]
    private ?message $message = null;

    #[ORM\Column(length: 255,name: 'file_name')]
    private ?string $fileName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?message
    {
        return $this->message;
    }

    public function setMessage(?message $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }
}
