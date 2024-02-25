<?php

namespace App\Entity;

use App\Repository\CommandeTrimestrielleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`commande_trimestrielle`')]
#[ORM\Entity(repositoryClass: CommandeTrimestrielleRepository::class)]
class CommandeTrimestrielle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'date_debut')]
    private ?\DateTimeInterface $DateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'date_fin')]
    private ?\DateTimeInterface $DateFin = null;

    #[ORM\OneToMany(mappedBy: 'CommandeTrimestrielle', targetEntity: MoisProjectionsAdmissions::class)]
    private Collection $moisProjectionsAdmissions;

    #[ORM\Column(nullable: true, name: 'is_active')]
    private ?bool $isActive = null;

    #[ORM\Column(length: 5, name: 'slug')]
    private ?string $Slug = null;

    #[ORM\OneToMany(mappedBy: 'CommandeTrimestrielle', targetEntity: RmaNut::class)]
    private Collection $rmaNuts;

    #[ORM\ManyToOne(inversedBy: 'commandeTrimestrielles')]
    #[ORM\JoinColumn(name: 'annee_previsionnelle_id')]
    private ?AnneePrevisionnelle $AnneePrevisionnelle = null;

    public function __construct()
    {
        $this->moisProjectionsAdmissions = new ArrayCollection();
        $this->rmaNuts = new ArrayCollection();
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): static
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): static
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function __toString()
    {
        return $this->Nom; // Remplacez par la propriété de votre classe que vous souhaitez afficher
    }

    /**
     * @return Collection<int, MoisProjectionsAdmissions>
     */
    public function getMoisProjectionsAdmissions(): Collection
    {
        return $this->moisProjectionsAdmissions;
    }

    public function addMoisProjectionsAdmission(MoisProjectionsAdmissions $moisProjectionsAdmission): static
    {
        if (!$this->moisProjectionsAdmissions->contains($moisProjectionsAdmission)) {
            $this->moisProjectionsAdmissions->add($moisProjectionsAdmission);
            $moisProjectionsAdmission->setCommandeTrimestrielle($this);
        }

        return $this;
    }

    public function removeMoisProjectionsAdmission(MoisProjectionsAdmissions $moisProjectionsAdmission): static
    {
        if ($this->moisProjectionsAdmissions->removeElement($moisProjectionsAdmission)) {
            // set the owning side to null (unless already changed)
            if ($moisProjectionsAdmission->getCommandeTrimestrielle() === $this) {
                $moisProjectionsAdmission->setCommandeTrimestrielle(null);
            }
        }

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->Slug;
    }

    public function setSlug(string $Slug): static
    {
        $this->Slug = $Slug;

        return $this;
    }

    /**
     * @return Collection<int, RmaNut>
     */
    public function getRmaNuts(): Collection
    {
        return $this->rmaNuts;
    }

    public function addRmaNut(RmaNut $rmaNut): static
    {
        if (!$this->rmaNuts->contains($rmaNut)) {
            $this->rmaNuts->add($rmaNut);
            $rmaNut->setCommandeTrimestrielle($this);
        }

        return $this;
    }

    public function removeRmaNut(RmaNut $rmaNut): static
    {
        if ($this->rmaNuts->removeElement($rmaNut)) {
            // set the owning side to null (unless already changed)
            if ($rmaNut->getCommandeTrimestrielle() === $this) {
                $rmaNut->setCommandeTrimestrielle(null);
            }
        }

        return $this;
    }

    public function getAnneePrevisionnelle(): ?AnneePrevisionnelle
    {
        return $this->AnneePrevisionnelle;
    }

    public function setAnneePrevisionnelle(?AnneePrevisionnelle $AnneePrevisionnelle): static
    {
        $this->AnneePrevisionnelle = $AnneePrevisionnelle;

        return $this;
    }
}
