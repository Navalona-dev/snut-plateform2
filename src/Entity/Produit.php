<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`produit`')]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: true, name: 'type_id')]
    private ?CentreRecuperation $Type = null;

    #[ORM\OneToMany(mappedBy: 'Produit', targetEntity: PvrdProduit::class)]
    private Collection $pvrdProduits;

    public function __construct()
    {
        $this->pvrdProduits = new ArrayCollection();
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

    public function getType(): ?CentreRecuperation
    {
        return $this->Type;
    }

    public function setType(?CentreRecuperation $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    /**
     * @return Collection<int, PvrdProduit>
     */
    public function getPvrdProduits(): Collection
    {
        return $this->pvrdProduits;
    }

    public function addPvrdProduit(PvrdProduit $pvrdProduit): static
    {
        if (!$this->pvrdProduits->contains($pvrdProduit)) {
            $this->pvrdProduits->add($pvrdProduit);
            $pvrdProduit->setProduit($this);
        }

        return $this;
    }

    public function removePvrdProduit(PvrdProduit $pvrdProduit): static
    {
        if ($this->pvrdProduits->removeElement($pvrdProduit)) {
            // set the owning side to null (unless already changed)
            if ($pvrdProduit->getProduit() === $this) {
                $pvrdProduit->setProduit(null);
            }
        }

        return $this;
    }
}
