<?php

namespace App\Entity;

use App\Repository\StockDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`stock_data`')]
#[ORM\Entity(repositoryClass: StockDataRepository::class)]
class StockData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name: 'rma_nut_id')]
    private ?RmaNut $rmaNut = null;

    #[ORM\Column(nullable: true, name: 'nombre_csb')]
    private ?int $nombreCSB = null;

    #[ORM\Column(nullable: true, name: 'nombre_rupture')]
    private ?int $nombreRupture = null;

    #[ORM\Column(nullable: true, name: 'nombre_soustock')]
    private ?int $nombreSoustock = null;

    #[ORM\Column(nullable: true, name: 'nombre_normal')]
    private ?int $nombreNormal = null;

    #[ORM\Column(nullable: true, name: 'nombre_sur_stock')]
    private ?int $nombreSurStock = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getNombreCSB(): ?int
    {
        return $this->nombreCSB;
    }

    public function setNombreCSB(?int $nombreCSB): static
    {
        $this->nombreCSB = $nombreCSB;

        return $this;
    }

    public function getNombreRupture(): ?int
    {
        return $this->nombreRupture;
    }

    public function setNombreRupture(?int $nombreRupture): static
    {
        $this->nombreRupture = $nombreRupture;

        return $this;
    }

    public function getNombreSoustock(): ?int
    {
        return $this->nombreSoustock;
    }

    public function setNombreSoustock(?int $nombreSoustock): static
    {
        $this->nombreSoustock = $nombreSoustock;

        return $this;
    }

    public function getNombreNormal(): ?int
    {
        return $this->nombreNormal;
    }

    public function setNombreNormal(?int $nombreNormal): static
    {
        $this->nombreNormal = $nombreNormal;

        return $this;
    }

    public function getNombreSurStock(): ?int
    {
        return $this->nombreSurStock;
    }

    public function setNombreSurStock(?int $nombreSurStock): static
    {
        $this->nombreSurStock = $nombreSurStock;

        return $this;
    }
}
