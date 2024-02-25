<?php

namespace App\Entity;

use App\Repository\DataValidationCrenasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`data_validation_crenas`')]
#[ORM\Entity(repositoryClass: DataValidationCrenasRepository::class)]
class DataValidationCrenas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'estimated_data_month_district')]
    private ?float $EstimatedDataMonthDistrict = null;

    #[ORM\Column(name: 'estimated_data_month_central')]
    private ?float $EstimatedDataMonthCentral = null;

    #[ORM\Column(name: 'validated_data_month')]
    private ?float $ValidatedDataMonth = null;

    #[ORM\ManyToOne(inversedBy: 'dataValidationCrenas')]
    #[ORM\JoinColumn(nullable: true, name: 'data_crenas_id')]
    private ?DataCrenas $DataCrenas = null;

    #[ORM\ManyToOne(inversedBy: 'dataValidationCrenas')]
    #[ORM\JoinColumn(nullable: true, name: 'mois_projections_admissions_id')]
    private ?MoisProjectionsAdmissions $MoisProjectionsAdmissions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstimatedDataMonthDistrict(): ?float
    {
        return $this->EstimatedDataMonthDistrict;
    }

    public function setEstimatedDataMonthDistrict(float $EstimatedDataMonthDistrict): static
    {
        $this->EstimatedDataMonthDistrict = $EstimatedDataMonthDistrict;

        return $this;
    }

    public function getEstimatedDataMonthCentral(): ?float
    {
        return $this->EstimatedDataMonthCentral;
    }

    public function setEstimatedDataMonthCentral(float $EstimatedDataMonthCentral): static
    {
        $this->EstimatedDataMonthCentral = $EstimatedDataMonthCentral;

        return $this;
    }

    public function getValidatedDataMonth(): ?float
    {
        return $this->ValidatedDataMonth;
    }

    public function setValidatedDataMonth(float $ValidatedDataMonth): static
    {
        $this->ValidatedDataMonth = $ValidatedDataMonth;

        return $this;
    }

    public function getDataCrenas(): ?DataCrenas
    {
        return $this->DataCrenas;
    }

    public function setDataCrenas(?DataCrenas $DataCrenas): static
    {
        $this->DataCrenas = $DataCrenas;

        return $this;
    }

    public function getMoisProjectionsAdmissions(): ?MoisProjectionsAdmissions
    {
        return $this->MoisProjectionsAdmissions;
    }

    public function setMoisProjectionsAdmissions(?MoisProjectionsAdmissions $MoisProjectionsAdmissions): static
    {
        $this->MoisProjectionsAdmissions = $MoisProjectionsAdmissions;

        return $this;
    }
}
