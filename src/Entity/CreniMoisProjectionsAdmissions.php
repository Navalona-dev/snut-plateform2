<?php

namespace App\Entity;

use App\Repository\CreniMoisProjectionsAdmissionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`creni_mois_projections_admissions`')]
#[ORM\Entity(repositoryClass: CreniMoisProjectionsAdmissionsRepository::class)]
class CreniMoisProjectionsAdmissions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, name: 'mois01_admission_creni_precedent')]
    private ?string $Mois01AdmissionCreniPrecedent = null;

    #[ORM\Column(length: 50, name: 'mois02_admission_creni_precedent')]
    private ?string $Mois02AdmissionCreniPrecedent = null;

    #[ORM\Column(length: 50, name: 'mois03_admission_creni_precedent')]
    private ?string $Mois03AdmissionCreniPrecedent = null;

    #[ORM\Column(length: 50, name: 'mois04_admission_creni_precedent')]
    private ?string $Mois04AdmissionCreniPrecedent = null;

    #[ORM\Column(length: 50, name: 'mois05_admission_creni_precedent')]
    private ?string $Mois05AdmissionCreniPrecedent = null;

    #[ORM\Column(length: 50, name: 'mois06_admission_creni_precedent')]
    private ?string $Mois06AdmissionCreniPrecedent = null;

    #[ORM\Column(length: 50, name: 'mois01_admission_projete_annee_precedent')]
    private ?string $Mois01AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(length: 50, name: 'mois02_admission_projete_annee_precedent')]
    private ?string $Mois02AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(length: 50, name: 'mois03_admission_projete_annee_precedent')]
    private ?string $Mois03AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(length: 50, name: 'mois04_admission_projete_annee_precedent')]
    private ?string $Mois04AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(length: 50, name: 'mois05_admission_projete_annee_precedent')]
    private ?string $Mois05AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(length: 50, name: 'mois06_admission_projete_annee_precedent')]
    private ?string $Mois06AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(length: 50, name: 'mois01_projection_annee_previsionnelle')]
    private ?string $Mois01ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(length: 50, name: 'mois02_projection_annee_previsionnelle')]
    private ?string $Mois02ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(length: 50, name: 'mois03_projection_annee_previsionnelle')]
    private ?string $Mois03ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(length: 50, name: 'mois04_projection_annee_previsionnelle')]
    private ?string $Mois04ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(length: 50, name: 'mois05_projection_annee_previsionnelle')]
    private ?string $Mois05ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(length: 50, name: 'mois06_projection_annee_previsionnelle')]
    private ?string $Mois06ProjectionAnneePrevisionnelle = null;

    #[ORM\ManyToOne(inversedBy: 'creniMoisProjectionsAdmissions')]
    #[ORM\JoinColumn(name: 'commande_semestrielle_id')]
    private ?CommandeSemestrielle $CommandeSemestrielle = null;

    #[ORM\OneToMany(mappedBy: 'CreniMoisProjectionsAdmissions', targetEntity: DataCreniMoisProjectionAdmission::class)]
    private Collection $dataCreniMoisProjectionAdmissions;

    #[ORM\OneToMany(mappedBy: 'CreniMoisProjectionsAdmissions', targetEntity: DataValidationCreni::class)]
    private Collection $dataValidationCrenis;

    public function __construct()
    {
        $this->dataCreniMoisProjectionAdmissions = new ArrayCollection();
        $this->dataValidationCrenis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMois01AdmissionCreniPrecedent(): ?string
    {
        return $this->Mois01AdmissionCreniPrecedent;
    }

    public function setMois01AdmissionCreniPrecedent(string $Mois01AdmissionCreniPrecedent): static
    {
        $this->Mois01AdmissionCreniPrecedent = $Mois01AdmissionCreniPrecedent;

        return $this;
    }

    public function getMois02AdmissionCreniPrecedent(): ?string
    {
        return $this->Mois02AdmissionCreniPrecedent;
    }

    public function setMois02AdmissionCreniPrecedent(string $Mois02AdmissionCreniPrecedent): static
    {
        $this->Mois02AdmissionCreniPrecedent = $Mois02AdmissionCreniPrecedent;

        return $this;
    }

    public function getMois03AdmissionCreniPrecedent(): ?string
    {
        return $this->Mois03AdmissionCreniPrecedent;
    }

    public function setMois03AdmissionCreniPrecedent(string $Mois03AdmissionCreniPrecedent): static
    {
        $this->Mois03AdmissionCreniPrecedent = $Mois03AdmissionCreniPrecedent;

        return $this;
    }

    public function getMois04AdmissionCreniPrecedent(): ?string
    {
        return $this->Mois04AdmissionCreniPrecedent;
    }

    public function setMois04AdmissionCreniPrecedent(string $Mois04AdmissionCreniPrecedent): static
    {
        $this->Mois04AdmissionCreniPrecedent = $Mois04AdmissionCreniPrecedent;

        return $this;
    }

    public function getMois05AdmissionCreniPrecedent(): ?string
    {
        return $this->Mois05AdmissionCreniPrecedent;
    }

    public function setMois05AdmissionCreniPrecedent(string $Mois05AdmissionCreniPrecedent): static
    {
        $this->Mois05AdmissionCreniPrecedent = $Mois05AdmissionCreniPrecedent;

        return $this;
    }

    public function getMois06AdmissionCreniPrecedent(): ?string
    {
        return $this->Mois06AdmissionCreniPrecedent;
    }

    public function setMois06AdmissionCreniPrecedent(string $Mois06AdmissionCreniPrecedent): static
    {
        $this->Mois06AdmissionCreniPrecedent = $Mois06AdmissionCreniPrecedent;

        return $this;
    }

    public function getMois01AdmissionProjeteAnneePrecedent(): ?string
    {
        return $this->Mois01AdmissionProjeteAnneePrecedent;
    }

    public function setMois01AdmissionProjeteAnneePrecedent(string $Mois01AdmissionProjeteAnneePrecedent): static
    {
        $this->Mois01AdmissionProjeteAnneePrecedent = $Mois01AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getMois02AdmissionProjeteAnneePrecedent(): ?string
    {
        return $this->Mois02AdmissionProjeteAnneePrecedent;
    }

    public function setMois02AdmissionProjeteAnneePrecedent(string $Mois02AdmissionProjeteAnneePrecedent): static
    {
        $this->Mois02AdmissionProjeteAnneePrecedent = $Mois02AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getMois03AdmissionProjeteAnneePrecedent(): ?string
    {
        return $this->Mois03AdmissionProjeteAnneePrecedent;
    }

    public function setMois03AdmissionProjeteAnneePrecedent(string $Mois03AdmissionProjeteAnneePrecedent): static
    {
        $this->Mois03AdmissionProjeteAnneePrecedent = $Mois03AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getMois04AdmissionProjeteAnneePrecedent(): ?string
    {
        return $this->Mois04AdmissionProjeteAnneePrecedent;
    }

    public function setMois04AdmissionProjeteAnneePrecedent(string $Mois04AdmissionProjeteAnneePrecedent): static
    {
        $this->Mois04AdmissionProjeteAnneePrecedent = $Mois04AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getMois05AdmissionProjeteAnneePrecedent(): ?string
    {
        return $this->Mois05AdmissionProjeteAnneePrecedent;
    }

    public function setMois05AdmissionProjeteAnneePrecedent(string $Mois05AdmissionProjeteAnneePrecedent): static
    {
        $this->Mois05AdmissionProjeteAnneePrecedent = $Mois05AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getMois06AdmissionProjeteAnneePrecedent(): ?string
    {
        return $this->Mois06AdmissionProjeteAnneePrecedent;
    }

    public function setMois06AdmissionProjeteAnneePrecedent(string $Mois06AdmissionProjeteAnneePrecedent): static
    {
        $this->Mois06AdmissionProjeteAnneePrecedent = $Mois06AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getMois01ProjectionAnneePrevisionnelle(): ?string
    {
        return $this->Mois01ProjectionAnneePrevisionnelle;
    }

    public function setMois01ProjectionAnneePrevisionnelle(string $Mois01ProjectionAnneePrevisionnelle): static
    {
        $this->Mois01ProjectionAnneePrevisionnelle = $Mois01ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getMois02ProjectionAnneePrevisionnelle(): ?string
    {
        return $this->Mois02ProjectionAnneePrevisionnelle;
    }

    public function setMois02ProjectionAnneePrevisionnelle(string $Mois02ProjectionAnneePrevisionnelle): static
    {
        $this->Mois02ProjectionAnneePrevisionnelle = $Mois02ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getMois03ProjectionAnneePrevisionnelle(): ?string
    {
        return $this->Mois03ProjectionAnneePrevisionnelle;
    }

    public function setMois03ProjectionAnneePrevisionnelle(string $Mois03ProjectionAnneePrevisionnelle): static
    {
        $this->Mois03ProjectionAnneePrevisionnelle = $Mois03ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getMois04ProjectionAnneePrevisionnelle(): ?string
    {
        return $this->Mois04ProjectionAnneePrevisionnelle;
    }

    public function setMois04ProjectionAnneePrevisionnelle(string $Mois04ProjectionAnneePrevisionnelle): static
    {
        $this->Mois04ProjectionAnneePrevisionnelle = $Mois04ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getMois05ProjectionAnneePrevisionnelle(): ?string
    {
        return $this->Mois05ProjectionAnneePrevisionnelle;
    }

    public function setMois05ProjectionAnneePrevisionnelle(string $Mois05ProjectionAnneePrevisionnelle): static
    {
        $this->Mois05ProjectionAnneePrevisionnelle = $Mois05ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getMois06ProjectionAnneePrevisionnelle(): ?string
    {
        return $this->Mois06ProjectionAnneePrevisionnelle;
    }

    public function setMois06ProjectionAnneePrevisionnelle(string $Mois06ProjectionAnneePrevisionnelle): static
    {
        $this->Mois06ProjectionAnneePrevisionnelle = $Mois06ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getCommandeSemestrielle(): ?CommandeSemestrielle
    {
        return $this->CommandeSemestrielle;
    }

    public function setCommandeSemestrielle(?CommandeSemestrielle $CommandeSemestrielle): static
    {
        $this->CommandeSemestrielle = $CommandeSemestrielle;

        return $this;
    }

    /**
     * @return Collection<int, DataCreniMoisProjectionAdmission>
     */
    public function getDataCreniMoisProjectionAdmissions(): Collection
    {
        return $this->dataCreniMoisProjectionAdmissions;
    }

    public function addDataCreniMoisProjectionAdmission(DataCreniMoisProjectionAdmission $dataCreniMoisProjectionAdmission): static
    {
        if (!$this->dataCreniMoisProjectionAdmissions->contains($dataCreniMoisProjectionAdmission)) {
            $this->dataCreniMoisProjectionAdmissions->add($dataCreniMoisProjectionAdmission);
            $dataCreniMoisProjectionAdmission->setCreniMoisProjectionsAdmissions($this);
        }

        return $this;
    }

    public function removeDataCreniMoisProjectionAdmission(DataCreniMoisProjectionAdmission $dataCreniMoisProjectionAdmission): static
    {
        if ($this->dataCreniMoisProjectionAdmissions->removeElement($dataCreniMoisProjectionAdmission)) {
            // set the owning side to null (unless already changed)
            if ($dataCreniMoisProjectionAdmission->getCreniMoisProjectionsAdmissions() === $this) {
                $dataCreniMoisProjectionAdmission->setCreniMoisProjectionsAdmissions(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DataValidationCreni>
     */
    public function getDataValidationCrenis(): Collection
    {
        return $this->dataValidationCrenis;
    }

    public function addDataValidationCreni(DataValidationCreni $dataValidationCreni): static
    {
        if (!$this->dataValidationCrenis->contains($dataValidationCreni)) {
            $this->dataValidationCrenis->add($dataValidationCreni);
            $dataValidationCreni->setCreniMoisProjectionsAdmissions($this);
        }

        return $this;
    }

    public function removeDataValidationCreni(DataValidationCreni $dataValidationCreni): static
    {
        if ($this->dataValidationCrenis->removeElement($dataValidationCreni)) {
            // set the owning side to null (unless already changed)
            if ($dataValidationCreni->getCreniMoisProjectionsAdmissions() === $this) {
                $dataValidationCreni->setCreniMoisProjectionsAdmissions(null);
            }
        }

        return $this;
    }
}
