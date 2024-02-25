<?php

namespace App\Entity;

use App\Repository\CommandeSemestrielleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`commande_semestrielle`')]
#[ORM\Entity(repositoryClass: CommandeSemestrielleRepository::class)]
class CommandeSemestrielle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'nom')]
    private ?string $Nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'date_debut')]
    private ?\DateTimeInterface $DateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'date_fin')]
    private ?\DateTimeInterface $DateFin = null;

    #[ORM\Column(name: 'is_active')]
    private ?bool $IsActive = null;

    #[ORM\Column(length: 255, name: 'slug')]
    private ?string $Slug = null;

    #[ORM\OneToMany(mappedBy: 'CommandeSemestrielle', targetEntity: CreniMoisProjectionsAdmissions::class)]
    private Collection $creniMoisProjectionsAdmissions;

    #[ORM\ManyToOne(inversedBy: 'commandeSemestrielles')]
    #[ORM\JoinColumn(nullable: true, name: 'annee_previsionnelle_id')]
    private ?AnneePrevisionnelle $AnneePrevisionnelle = null;

    public function __construct()
    {
        $this->creniMoisProjectionsAdmissions = new ArrayCollection();
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

    public function isIsActive(): ?bool
    {
        return $this->IsActive;
    }

    public function setIsActive(bool $IsActive): static
    {
        $this->IsActive = $IsActive;

        return $this;
    }

    public function __toString()
    {
        return $this->Nom; // Remplacez par la propriété de votre classe que vous souhaitez afficher
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
     * @return Collection<int, CreniMoisProjectionsAdmissions>
     */
    public function getCreniMoisProjectionsAdmissions(): Collection
    {
        return $this->creniMoisProjectionsAdmissions;
    }

    public function addCreniMoisProjectionsAdmission(CreniMoisProjectionsAdmissions $creniMoisProjectionsAdmission): static
    {
        if (!$this->creniMoisProjectionsAdmissions->contains($creniMoisProjectionsAdmission)) {
            $this->creniMoisProjectionsAdmissions->add($creniMoisProjectionsAdmission);
            $creniMoisProjectionsAdmission->setCommandeSemestrielle($this);
        }

        return $this;
    }

    public function removeCreniMoisProjectionsAdmission(CreniMoisProjectionsAdmissions $creniMoisProjectionsAdmission): static
    {
        if ($this->creniMoisProjectionsAdmissions->removeElement($creniMoisProjectionsAdmission)) {
            // set the owning side to null (unless already changed)
            if ($creniMoisProjectionsAdmission->getCommandeSemestrielle() === $this) {
                $creniMoisProjectionsAdmission->setCommandeSemestrielle(null);
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
