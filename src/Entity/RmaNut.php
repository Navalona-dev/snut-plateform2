<?php

namespace App\Entity;

use App\Repository\RmaNutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`rma_nut`')]
#[ORM\Entity(repositoryClass: RmaNutRepository::class)]
class RmaNut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'uploaded_date')]
    private ?\DateTimeInterface $uploadedDate = null;

    #[ORM\Column(length: 255, name: 'original_file_name')]
    private ?string $originalFileName = null;

    #[ORM\Column(length: 255, name: 'new_file_name')]
    private ?string $newFileName = null;

    #[ORM\ManyToOne(inversedBy: 'rmaNuts')]
    #[ORM\JoinColumn(nullable: false, name: 'uploaded_by_id')]
    private ?User $uploadedBy = null;

    #[ORM\ManyToOne(inversedBy: 'rmaNuts')]
    #[ORM\JoinColumn(nullable: false, name: 'commande_trimestrielle_id')]
    private ?CommandeTrimestrielle $CommandeTrimestrielle = null;

    #[ORM\ManyToOne(inversedBy: 'rmaNuts')]
    #[ORM\JoinColumn(nullable: true, name: 'district_id')]
    private ?District $District = null;

    #[ORM\ManyToOne(inversedBy: 'rmaNuts')]
    #[ORM\JoinColumn(nullable: true, name: 'region_id')]
    private ?Region $Region = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUploadedDate(): ?\DateTimeInterface
    {
        return $this->uploadedDate;
    }

    public function setUploadedDate(\DateTimeInterface $uploadedDate): static
    {
        $this->uploadedDate = $uploadedDate;

        return $this;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(string $originalFileName): static
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    public function getNewFileName(): ?string
    {
        return $this->newFileName;
    }

    public function setNewFileName(string $newFileName): static
    {
        $this->newFileName = $newFileName;

        return $this;
    }

    public function getUploadedBy(): ?User
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy(?User $uploadedBy): static
    {
        $this->uploadedBy = $uploadedBy;

        return $this;
    }

    public function getCommandeTrimestrielle(): ?CommandeTrimestrielle
    {
        return $this->CommandeTrimestrielle;
    }

    public function setCommandeTrimestrielle(?CommandeTrimestrielle $CommandeTrimestrielle): static
    {
        $this->CommandeTrimestrielle = $CommandeTrimestrielle;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->District;
    }

    public function setDistrict(?District $District): static
    {
        $this->District = $District;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->Region;
    }

    public function setRegion(?Region $Region): static
    {
        $this->Region = $Region;

        return $this;
    }
}
