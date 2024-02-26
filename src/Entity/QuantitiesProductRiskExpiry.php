<?php

namespace App\Entity;

use App\Repository\QuantitiesProductRiskExpiryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`quantities_product_risk_expiry`')]
#[ORM\Entity(repositoryClass: QuantitiesProductRiskExpiryRepository::class)]
class QuantitiesProductRiskExpiry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true, name: 'pn')]
    private ?string $PN = null;

    #[ORM\Column(length: 255, nullable: true, name: 'amoxi')]
    private ?string $AMOXI = null;

    #[ORM\Column(length: 255, nullable: true, name: 'f75')]
    private ?string $F75 = null;

    #[ORM\Column(length: 255, nullable: true, name: 'f100')]
    private ?string $F100 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name: 'rma_nut_id')]
    private ?RmaNut $rmaNut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPN(): ?string
    {
        return $this->PN;
    }

    public function setPN(?string $PN): static
    {
        $this->PN = $PN;

        return $this;
    }

    public function getAMOXI(): ?string
    {
        return $this->AMOXI;
    }

    public function setAMOXI(?string $AMOXI): static
    {
        $this->AMOXI = $AMOXI;

        return $this;
    }

    public function getF75(): ?string
    {
        return $this->F75;
    }

    public function setF75(?string $F75): static
    {
        $this->F75 = $F75;

        return $this;
    }

    public function getF100(): ?string
    {
        return $this->F100;
    }

    public function setF100(?string $F100): static
    {
        $this->F100 = $F100;

        return $this;
    }

    public function getRmaNut(): ?RmaNut
    {
        return $this->rmaNut;
    }

    public function setRmaNut(?RmaNut $rmaNut): static
    {
        $this->rmaNut = $rmaNut;

        return $this;
    }
}
