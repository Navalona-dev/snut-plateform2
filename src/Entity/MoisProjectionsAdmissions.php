<?php

namespace App\Entity;

use App\Repository\MoisProjectionsAdmissionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`mois_projections_admissions`')]
#[ORM\Entity(repositoryClass: MoisProjectionsAdmissionsRepository::class)]
class MoisProjectionsAdmissions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, name: 'mois_admission_crenasannee_precedent')]
    private ?string $MoisAdmissionCRENASAnneePrecedent = null;

    #[ORM\Column(length: 50, name: 'mois_admission_projete_annee_precedent')]
    private ?string $MoisAdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(length: 50, name: 'mois_projection_annee_previsionnelle')]
    private ?string $MoisProjectionAnneePrevisionnelle = null;

    #[ORM\ManyToOne(inversedBy: 'moisProjectionsAdmissions')]
    #[ORM\JoinColumn(name: 'groupe_id')]
    private ?Groupe $Groupe = null;

    #[ORM\ManyToOne(inversedBy: 'moisProjectionsAdmissions')]
    #[ORM\JoinColumn(name: 'commande_trimestrielle_id')]
    private ?CommandeTrimestrielle $CommandeTrimestrielle = null;

    #[ORM\OneToMany(mappedBy: 'MoisProjectionsAdmissions', targetEntity: DataCrenasMoisProjectionAdmission::class)]
    private Collection $dataCrenasMoisProjectionAdmissions;

    #[ORM\OneToMany(mappedBy: 'MoisProjectionsAdmissions', targetEntity: DataValidationCrenas::class)]
    private Collection $dataValidationCrenas;

    public function __construct()
    {
        $this->dataCrenasMoisProjectionAdmissions = new ArrayCollection();
        $this->dataValidationCrenas = new ArrayCollection();
    } 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoisAdmissionCRENASAnneePrecedent(): ?string
    {
        return $this->MoisAdmissionCRENASAnneePrecedent;
    }

    public function setMoisAdmissionCRENASAnneePrecedent(string $MoisAdmissionCRENASAnneePrecedent): static
    {
        $this->MoisAdmissionCRENASAnneePrecedent = $MoisAdmissionCRENASAnneePrecedent;

        return $this;
    }

    public function getMoisAdmissionProjeteAnneePrecedent(): ?string
    {
        return $this->MoisAdmissionProjeteAnneePrecedent;
    }

    public function setMoisAdmissionProjeteAnneePrecedent(string $MoisAdmissionProjeteAnneePrecedent): static
    {
        $this->MoisAdmissionProjeteAnneePrecedent = $MoisAdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getMoisProjectionAnneePrevisionnelle(): ?string
    {
        return $this->MoisProjectionAnneePrevisionnelle;
    }

    public function setMoisProjectionAnneePrevisionnelle(string $MoisProjectionAnneePrevisionnelle): static
    {
        $this->MoisProjectionAnneePrevisionnelle = $MoisProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->Groupe;
    }

    public function setGroupe(?Groupe $Groupe): static
    {
        $this->Groupe = $Groupe;

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

    /**
     * @return Collection<int, DataCrenasMoisProjectionAdmission>
     */
    public function getDataCrenasMoisProjectionAdmissions(): Collection
    {
        return $this->dataCrenasMoisProjectionAdmissions;
    }

    public function addDataCrenasMoisProjectionAdmission(DataCrenasMoisProjectionAdmission $dataCrenasMoisProjectionAdmission): static
    {
        if (!$this->dataCrenasMoisProjectionAdmissions->contains($dataCrenasMoisProjectionAdmission)) {
            $this->dataCrenasMoisProjectionAdmissions->add($dataCrenasMoisProjectionAdmission);
            $dataCrenasMoisProjectionAdmission->setMoisProjectionsAdmissions($this);
        }

        return $this;
    }

    public function removeDataCrenasMoisProjectionAdmission(DataCrenasMoisProjectionAdmission $dataCrenasMoisProjectionAdmission): static
    {
        if ($this->dataCrenasMoisProjectionAdmissions->removeElement($dataCrenasMoisProjectionAdmission)) {
            // set the owning side to null (unless already changed)
            if ($dataCrenasMoisProjectionAdmission->getMoisProjectionsAdmissions() === $this) {
                $dataCrenasMoisProjectionAdmission->setMoisProjectionsAdmissions(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DataValidationCrenas>
     */
    public function getDataValidationCrenas(): Collection
    {
        return $this->dataValidationCrenas;
    }

    public function addDataValidationCrena(DataValidationCrenas $dataValidationCrena): static
    {
        if (!$this->dataValidationCrenas->contains($dataValidationCrena)) {
            $this->dataValidationCrenas->add($dataValidationCrena);
            $dataValidationCrena->setMoisProjectionsAdmissions($this);
        }

        return $this;
    }

    public function removeDataValidationCrena(DataValidationCrenas $dataValidationCrena): static
    {
        if ($this->dataValidationCrenas->removeElement($dataValidationCrena)) {
            // set the owning side to null (unless already changed)
            if ($dataValidationCrena->getMoisProjectionsAdmissions() === $this) {
                $dataValidationCrena->setMoisProjectionsAdmissions(null);
            }
        }

        return $this;
    } 
}
