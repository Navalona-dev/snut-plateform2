<?php

namespace App\Entity;

use App\Repository\DataValidationCreniRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`data_validation_creni`')]
#[ORM\Entity(repositoryClass: DataValidationCreniRepository::class)]
class DataValidationCreni
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'data_mois01_projection_estimated_district')]
    private ?float $DataMois01ProjectionEstimatedDistrict = null;

    #[ORM\Column(name: 'data_mois02_projection_estimated_district')]
    private ?float $DataMois02ProjectionEstimatedDistrict = null;

    #[ORM\Column(name: 'data_mois03_projection_estimated_district')]
    private ?float $DataMois03ProjectionEstimatedDistrict = null;

    #[ORM\Column(name: 'data_mois04_projection_estimated_district')]
    private ?float $DataMois04ProjectionEstimatedDistrict = null;

    #[ORM\Column(name: 'data_mois05_projection_estimated_district')]
    private ?float $DataMois05ProjectionEstimatedDistrict = null;

    #[ORM\Column(name: 'data_mois06_projection_estimated_district')]
    private ?float $DataMois06ProjectionEstimatedDistrict = null;

    #[ORM\Column(name: 'data_mois01_projection_estimated_central')]
    private ?float $DataMois01ProjectionEstimatedCentral = null;

    #[ORM\Column(name: 'data_mois02_projection_estimated_central')]
    private ?float $DataMois02ProjectionEstimatedCentral = null;

    #[ORM\Column(name: 'data_mois03_projection_estimated_central')]
    private ?float $DataMois03ProjectionEstimatedCentral = null;

    #[ORM\Column(name: 'data_mois04_projection_estimated_central')]
    private ?float $DataMois04ProjectionEstimatedCentral = null;

    #[ORM\Column(name: 'data_mois05_projection_estimated_central')]
    private ?float $DataMois05ProjectionEstimatedCentral = null;

    #[ORM\Column(name: 'data_mois06_projection_estimated_central')]
    private ?float $DataMois06ProjectionEstimatedCentral = null;

    #[ORM\Column(name: 'data_mois01_projection_validated')]
    private ?float $DataMois01ProjectionValidated = null;

    #[ORM\Column(name: 'data_mois02_projection_validated')]
    private ?float $DataMois02ProjectionValidated = null;

    #[ORM\Column(name: 'data_mois03_projection_validated')]
    private ?float $DataMois03ProjectionValidated = null;

    #[ORM\Column(name: 'data_mois04_projection_validated')]
    private ?float $DataMois04ProjectionValidated = null;

    #[ORM\Column(name: 'data_mois05_projection_validated')]
    private ?float $DataMois05ProjectionValidated = null;

    #[ORM\Column(name: 'data_mois06_projection_validated')]
    private ?float $DataMois06ProjectionValidated = null;

    #[ORM\ManyToOne(inversedBy: 'dataValidationCrenis')]
    #[ORM\JoinColumn(nullable: true, name: 'data_crena_id')]
    private ?DataCreni $DataCreni = null;

    #[ORM\ManyToOne(inversedBy: 'dataValidationCrenis')]
    #[ORM\JoinColumn(nullable: true, name: 'creni_mois_projections_admissions_id')]
    private ?CreniMoisProjectionsAdmissions $CreniMoisProjectionsAdmissions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataMois01ProjectionEstimatedDistrict(): ?float
    {
        return $this->DataMois01ProjectionEstimatedDistrict;
    }

    public function setDataMois01ProjectionEstimatedDistrict(float $DataMois01ProjectionEstimatedDistrict): static
    {
        $this->DataMois01ProjectionEstimatedDistrict = $DataMois01ProjectionEstimatedDistrict;

        return $this;
    }

    public function getDataMois02ProjectionEstimatedDistrict(): ?float
    {
        return $this->DataMois02ProjectionEstimatedDistrict;
    }

    public function setDataMois02ProjectionEstimatedDistrict(float $DataMois02ProjectionEstimatedDistrict): static
    {
        $this->DataMois02ProjectionEstimatedDistrict = $DataMois02ProjectionEstimatedDistrict;

        return $this;
    }

    public function getDataMois03ProjectionEstimatedDistrict(): ?float
    {
        return $this->DataMois03ProjectionEstimatedDistrict;
    }

    public function setDataMois03ProjectionEstimatedDistrict(float $DataMois03ProjectionEstimatedDistrict): static
    {
        $this->DataMois03ProjectionEstimatedDistrict = $DataMois03ProjectionEstimatedDistrict;

        return $this;
    }

    public function getDataMois04ProjectionEstimatedDistrict(): ?float
    {
        return $this->DataMois04ProjectionEstimatedDistrict;
    }

    public function setDataMois04ProjectionEstimatedDistrict(float $DataMois04ProjectionEstimatedDistrict): static
    {
        $this->DataMois04ProjectionEstimatedDistrict = $DataMois04ProjectionEstimatedDistrict;

        return $this;
    }

    public function getDataMois05ProjectionEstimatedDistrict(): ?float
    {
        return $this->DataMois05ProjectionEstimatedDistrict;
    }

    public function setDataMois05ProjectionEstimatedDistrict(float $DataMois05ProjectionEstimatedDistrict): static
    {
        $this->DataMois05ProjectionEstimatedDistrict = $DataMois05ProjectionEstimatedDistrict;

        return $this;
    }

    public function getDataMois06ProjectionEstimatedDistrict(): ?float
    {
        return $this->DataMois06ProjectionEstimatedDistrict;
    }

    public function setDataMois06ProjectionEstimatedDistrict(float $DataMois06ProjectionEstimatedDistrict): static
    {
        $this->DataMois06ProjectionEstimatedDistrict = $DataMois06ProjectionEstimatedDistrict;

        return $this;
    }

    public function getDataMois01ProjectionEstimatedCentral(): ?float
    {
        return $this->DataMois01ProjectionEstimatedCentral;
    }

    public function setDataMois01ProjectionEstimatedCentral(float $DataMois01ProjectionEstimatedCentral): static
    {
        $this->DataMois01ProjectionEstimatedCentral = $DataMois01ProjectionEstimatedCentral;

        return $this;
    }

    public function getDataMois02ProjectionEstimatedCentral(): ?float
    {
        return $this->DataMois02ProjectionEstimatedCentral;
    }

    public function setDataMois02ProjectionEstimatedCentral(float $DataMois02ProjectionEstimatedCentral): static
    {
        $this->DataMois02ProjectionEstimatedCentral = $DataMois02ProjectionEstimatedCentral;

        return $this;
    }

    public function getDataMois03ProjectionEstimatedCentral(): ?float
    {
        return $this->DataMois03ProjectionEstimatedCentral;
    }

    public function setDataMois03ProjectionEstimatedCentral(float $DataMois03ProjectionEstimatedCentral): static
    {
        $this->DataMois03ProjectionEstimatedCentral = $DataMois03ProjectionEstimatedCentral;

        return $this;
    }

    public function getDataMois04ProjectionEstimatedCentral(): ?float
    {
        return $this->DataMois04ProjectionEstimatedCentral;
    }

    public function setDataMois04ProjectionEstimatedCentral(float $DataMois04ProjectionEstimatedCentral): static
    {
        $this->DataMois04ProjectionEstimatedCentral = $DataMois04ProjectionEstimatedCentral;

        return $this;
    }

    public function getDataMois05ProjectionEstimatedCentral(): ?float
    {
        return $this->DataMois05ProjectionEstimatedCentral;
    }

    public function setDataMois05ProjectionEstimatedCentral(float $DataMois05ProjectionEstimatedCentral): static
    {
        $this->DataMois05ProjectionEstimatedCentral = $DataMois05ProjectionEstimatedCentral;

        return $this;
    }

    public function getDataMois06ProjectionEstimatedCentral(): ?float
    {
        return $this->DataMois06ProjectionEstimatedCentral;
    }

    public function setDataMois06ProjectionEstimatedCentral(float $DataMois06ProjectionEstimatedCentral): static
    {
        $this->DataMois06ProjectionEstimatedCentral = $DataMois06ProjectionEstimatedCentral;

        return $this;
    }

    public function getDataMois01ProjectionValidated(): ?float
    {
        return $this->DataMois01ProjectionValidated;
    }

    public function setDataMois01ProjectionValidated(float $DataMois01ProjectionValidated): static
    {
        $this->DataMois01ProjectionValidated = $DataMois01ProjectionValidated;

        return $this;
    }

    public function getDataMois02ProjectionValidated(): ?float
    {
        return $this->DataMois02ProjectionValidated;
    }

    public function setDataMois02ProjectionValidated(float $DataMois02ProjectionValidated): static
    {
        $this->DataMois02ProjectionValidated = $DataMois02ProjectionValidated;

        return $this;
    }

    public function getDataMois03ProjectionValidated(): ?float
    {
        return $this->DataMois03ProjectionValidated;
    }

    public function setDataMois03ProjectionValidated(float $DataMois03ProjectionValidated): static
    {
        $this->DataMois03ProjectionValidated = $DataMois03ProjectionValidated;

        return $this;
    }

    public function getDataMois04ProjectionValidated(): ?float
    {
        return $this->DataMois04ProjectionValidated;
    }

    public function setDataMois04ProjectionValidated(float $DataMois04ProjectionValidated): static
    {
        $this->DataMois04ProjectionValidated = $DataMois04ProjectionValidated;

        return $this;
    }

    public function getDataMois05ProjectionValidated(): ?float
    {
        return $this->DataMois05ProjectionValidated;
    }

    public function setDataMois05ProjectionValidated(float $DataMois05ProjectionValidated): static
    {
        $this->DataMois05ProjectionValidated = $DataMois05ProjectionValidated;

        return $this;
    }

    public function getDataMois06ProjectionValidated(): ?float
    {
        return $this->DataMois06ProjectionValidated;
    }

    public function setDataMois06ProjectionValidated(float $DataMois06ProjectionValidated): static
    {
        $this->DataMois06ProjectionValidated = $DataMois06ProjectionValidated;

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
