<?php

namespace App\Entity;

use App\Repository\CentreRecuperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`centre_recuperation`')]
#[ORM\Entity(repositoryClass: CentreRecuperationRepository::class)]
class CentreRecuperation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'nom')]
    private ?string $Nom = null;

    #[ORM\Column(length: 50, name: 'sigle')]
    private ?string $Sigle = null;

    #[ORM\OneToMany(mappedBy: 'Type', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->Nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->Sigle;
    }

    public function setSigle(string $Sigle): static
    {
        $this->Sigle = $Sigle;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setType($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getType() === $this) {
                $produit->setType(null);
            }
        }

        return $this;
    }
}
