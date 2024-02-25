<?php

namespace App\Entity;

use App\Repository\DataCreniMoisProjectionAdmissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`data_creni_mois_projection_admission`')]
#[ORM\Entity(repositoryClass: DataCreniMoisProjectionAdmissionRepository::class)]
class DataCreniMoisProjectionAdmission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'data_mois01_admission_creni_precedent')]
    private ?float $DataMois01AdmissionCreniPrecedent = null;

    #[ORM\Column(name: 'data_mois02_admission_creni_precedent')]
    private ?float $DataMois02AdmissionCreniPrecedent = null;

    #[ORM\Column(name: 'data_mois03_admission_creni_precedent')]
    private ?float $DataMois03AdmissionCreniPrecedent = null;

    #[ORM\Column(name: 'data_mois04_admission_creni_precedent')]
    private ?float $DataMois04AdmissionCreniPrecedent = null;

    #[ORM\Column(name: 'data_mois05_admission_creni_precedent')]
    private ?float $DataMois05AdmissionCreniPrecedent = null;

    #[ORM\Column(name: 'data_mois06_admission_creni_precedent')]
    private ?float $DataMois06AdmissionCreniPrecedent = null;

    #[ORM\Column(name: 'data_mois01_admission_projete_annee_precedent')]
    private ?float $DataMois01AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(name: 'data_mois02_admission_projete_annee_precedent')]
    private ?float $DataMois02AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(name: 'data_mois03_admission_projete_annee_precedent')]
    private ?float $DataMois03AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(name: 'data_mois04_admission_projete_annee_precedent')]
    private ?float $DataMois04AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(name: 'data_mois05_admission_projete_annee_precedent')]
    private ?float $DataMois05AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(name: 'data_mois06_admission_projete_annee_precedent')]
    private ?float $DataMois06AdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(name: 'data_mois01_projection_annee_previsionnelle')]
    private ?float $DataMois01ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(name: 'data_mois02_projection_annee_previsionnelle')]
    private ?float $DataMois02ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(name: 'data_mois03_projection_annee_previsionnelle')]
    private ?float $DataMois03ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(name: 'data_mois04_projection_annee_previsionnelle')]
    private ?float $DataMois04ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(name: 'data_mois05_projection_annee_previsionnelle')]
    private ?float $DataMois05ProjectionAnneePrevisionnelle = null;

    #[ORM\Column(name: 'data_mois06_projection_annee_previsionnelle')]
    private ?float $DataMois06ProjectionAnneePrevisionnelle = null;

    #[ORM\ManyToOne(inversedBy: 'dataCreniMoisProjectionAdmissions')]
    #[ORM\JoinColumn(name: 'data_creni_id')]
    private ?DataCreni $DataCreni = null;

    #[ORM\ManyToOne(inversedBy: 'dataCreniMoisProjectionAdmissions')]
    #[ORM\JoinColumn(name: 'creni_mois_projections_admissions_id')]
    private ?CreniMoisProjectionsAdmissions $CreniMoisProjectionsAdmissions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataMois01AdmissionCreniPrecedent(): ?float
    {
        return $this->DataMois01AdmissionCreniPrecedent;
    }

    public function setDataMois01AdmissionCreniPrecedent(float $DataMois01AdmissionCreniPrecedent): static
    {
        $this->DataMois01AdmissionCreniPrecedent = $DataMois01AdmissionCreniPrecedent;

        return $this;
    }

    public function getDataMois02AdmissionCreniPrecedent(): ?float
    {
        return $this->DataMois02AdmissionCreniPrecedent;
    }

    public function setDataMois02AdmissionCreniPrecedent(float $DataMois02AdmissionCreniPrecedent): static
    {
        $this->DataMois02AdmissionCreniPrecedent = $DataMois02AdmissionCreniPrecedent;

        return $this;
    }

    public function getDataMois03AdmissionCreniPrecedent(): ?float
    {
        return $this->DataMois03AdmissionCreniPrecedent;
    }

    public function setDataMois03AdmissionCreniPrecedent(float $DataMois03AdmissionCreniPrecedent): static
    {
        $this->DataMois03AdmissionCreniPrecedent = $DataMois03AdmissionCreniPrecedent;

        return $this;
    }

    public function getDataMois04AdmissionCreniPrecedent(): ?float
    {
        return $this->DataMois04AdmissionCreniPrecedent;
    }

    public function setDataMois04AdmissionCreniPrecedent(float $DataMois04AdmissionCreniPrecedent): static
    {
        $this->DataMois04AdmissionCreniPrecedent = $DataMois04AdmissionCreniPrecedent;

        return $this;
    }

    public function getDataMois05AdmissionCreniPrecedent(): ?float
    {
        return $this->DataMois05AdmissionCreniPrecedent;
    }

    public function setDataMois05AdmissionCreniPrecedent(float $DataMois05AdmissionCreniPrecedent): static
    {
        $this->DataMois05AdmissionCreniPrecedent = $DataMois05AdmissionCreniPrecedent;

        return $this;
    }

    public function getDataMois06AdmissionCreniPrecedent(): ?float
    {
        return $this->DataMois06AdmissionCreniPrecedent;
    }

    public function setDataMois06AdmissionCreniPrecedent(float $DataMois06AdmissionCreniPrecedent): static
    {
        $this->DataMois06AdmissionCreniPrecedent = $DataMois06AdmissionCreniPrecedent;

        return $this;
    }

    public function getDataMois01AdmissionProjeteAnneePrecedent(): ?float
    {
        return $this->DataMois01AdmissionProjeteAnneePrecedent;
    }

    public function setDataMois01AdmissionProjeteAnneePrecedent(float $DataMois01AdmissionProjeteAnneePrecedent): static
    {
        $this->DataMois01AdmissionProjeteAnneePrecedent = $DataMois01AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getDataMois02AdmissionProjeteAnneePrecedent(): ?float
    {
        return $this->DataMois02AdmissionProjeteAnneePrecedent;
    }

    public function setDataMois02AdmissionProjeteAnneePrecedent(float $DataMois02AdmissionProjeteAnneePrecedent): static
    {
        $this->DataMois02AdmissionProjeteAnneePrecedent = $DataMois02AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getDataMois03AdmissionProjeteAnneePrecedent(): ?float
    {
        return $this->DataMois03AdmissionProjeteAnneePrecedent;
    }

    public function setDataMois03AdmissionProjeteAnneePrecedent(float $DataMois03AdmissionProjeteAnneePrecedent): static
    {
        $this->DataMois03AdmissionProjeteAnneePrecedent = $DataMois03AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getDataMois04AdmissionProjeteAnneePrecedent(): ?float
    {
        return $this->DataMois04AdmissionProjeteAnneePrecedent;
    }

    public function setDataMois04AdmissionProjeteAnneePrecedent(float $DataMois04AdmissionProjeteAnneePrecedent): static
    {
        $this->DataMois04AdmissionProjeteAnneePrecedent = $DataMois04AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getDataMois05AdmissionProjeteAnneePrecedent(): ?float
    {
        return $this->DataMois05AdmissionProjeteAnneePrecedent;
    }

    public function setDataMois05AdmissionProjeteAnneePrecedent(float $DataMois05AdmissionProjeteAnneePrecedent): static
    {
        $this->DataMois05AdmissionProjeteAnneePrecedent = $DataMois05AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getDataMois06AdmissionProjeteAnneePrecedent(): ?float
    {
        return $this->DataMois06AdmissionProjeteAnneePrecedent;
    }

    public function setDataMois06AdmissionProjeteAnneePrecedent(float $DataMois06AdmissionProjeteAnneePrecedent): static
    {
        $this->DataMois06AdmissionProjeteAnneePrecedent = $DataMois06AdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getDataMois01ProjectionAnneePrevisionnelle(): ?float
    {
        return $this->DataMois01ProjectionAnneePrevisionnelle;
    }

    public function setDataMois01ProjectionAnneePrevisionnelle(float $DataMois01ProjectionAnneePrevisionnelle): static
    {
        $this->DataMois01ProjectionAnneePrevisionnelle = $DataMois01ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getDataMois02ProjectionAnneePrevisionnelle(): ?float
    {
        return $this->DataMois02ProjectionAnneePrevisionnelle;
    }

    public function setDataMois02ProjectionAnneePrevisionnelle(float $DataMois02ProjectionAnneePrevisionnelle): static
    {
        $this->DataMois02ProjectionAnneePrevisionnelle = $DataMois02ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getDataMois03ProjectionAnneePrevisionnelle(): ?float
    {
        return $this->DataMois03ProjectionAnneePrevisionnelle;
    }

    public function setDataMois03ProjectionAnneePrevisionnelle(float $DataMois03ProjectionAnneePrevisionnelle): static
    {
        $this->DataMois03ProjectionAnneePrevisionnelle = $DataMois03ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getDataMois04ProjectionAnneePrevisionnelle(): ?float
    {
        return $this->DataMois04ProjectionAnneePrevisionnelle;
    }

    public function setDataMois04ProjectionAnneePrevisionnelle(float $DataMois04ProjectionAnneePrevisionnelle): static
    {
        $this->DataMois04ProjectionAnneePrevisionnelle = $DataMois04ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getDataMois05ProjectionAnneePrevisionnelle(): ?float
    {
        return $this->DataMois05ProjectionAnneePrevisionnelle;
    }

    public function setDataMois05ProjectionAnneePrevisionnelle(float $DataMois05ProjectionAnneePrevisionnelle): static
    {
        $this->DataMois05ProjectionAnneePrevisionnelle = $DataMois05ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getDataMois06ProjectionAnneePrevisionnelle(): ?float
    {
        return $this->DataMois06ProjectionAnneePrevisionnelle;
    }

    public function setDataMois06ProjectionAnneePrevisionnelle(float $DataMois06ProjectionAnneePrevisionnelle): static
    {
        $this->DataMois06ProjectionAnneePrevisionnelle = $DataMois06ProjectionAnneePrevisionnelle;

        return $this;
    }

    public function getDataCreni(): ?DataCreni
    {
        return $this->DataCreni;
    }

    public function setDataCreni(?DataCreni $DataCreni): static
    {
        $this->DataCreni = $DataCreni;

        return $this;
    }

    public function getCreniMoisProjectionsAdmissions(): ?CreniMoisProjectionsAdmissions
    {
        return $this->CreniMoisProjectionsAdmissions;
    }

    public function setCreniMoisProjectionsAdmissions(?CreniMoisProjectionsAdmissions $CreniMoisProjectionsAdmissions): static
    {
        $this->CreniMoisProjectionsAdmissions = $CreniMoisProjectionsAdmissions;

        return $this;
    }
}
