<?php

namespace App\Entity;

use App\Repository\PvrdProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`pvrd_produit`')]
#[ORM\Entity(repositoryClass: PvrdProduitRepository::class)]
class PvrdProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $QuantiteInscritSurBL = null;

    #[ORM\Column]
    private ?float $QuantiteRecue = null;

    #[ORM\Column]
    private ?float $EcartEntreQuantite = null;

    #[ORM\ManyToOne(inversedBy: 'pvrdProduits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pvrd $Pvrd = null;

    #[ORM\Column(length: 255)]
    private ?string $Periode = null;

    #[ORM\ManyToOne(inversedBy: 'pvrdProduits')]
    #[ORM\JoinColumn(nullable: true, name: 'produit_id')]
    private ?Produit $Produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteInscritSurBL(): ?float
    {
        return $this->QuantiteInscritSurBL;
    }

    public function setQuantiteInscritSurBL(float $QuantiteInscritSurBL): static
    {
        $this->QuantiteInscritSurBL = $QuantiteInscritSurBL;

        return $this;
    }

    public function getQuantiteRecue(): ?float
    {
        return $this->QuantiteRecue;
    }

    public function setQuantiteRecue(float $QuantiteRecue): static
    {
        $this->QuantiteRecue = $QuantiteRecue;

        return $this;
    }

    public function getEcartEntreQuantite(): ?float
    {
        return $this->EcartEntreQuantite;
    }

    public function setEcartEntreQuantite(float $EcartEntreQuantite): static
    {
        $this->EcartEntreQuantite = $EcartEntreQuantite;

        return $this;
    }

    public function getPvrd(): ?Pvrd
    {
        return $this->Pvrd;
    }

    public function setPvrd(?Pvrd $Pvrd): static
    {
        $this->Pvrd = $Pvrd;

        return $this;
    }

    public function getPeriode(): ?string
    {
        return $this->Periode;
    }

    public function setPeriode(string $Periode): static
    {
        $this->Periode = $Periode;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->Produit;
    }

    public function setProduit(?Produit $Produit): static
    {
        $this->Produit = $Produit;

        return $this;
    }
}
