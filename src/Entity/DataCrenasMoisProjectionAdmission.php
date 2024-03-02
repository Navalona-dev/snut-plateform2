<?php

namespace App\Entity;

use App\Repository\DataCrenasMoisProjectionAdmissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`data_crenas_mois_projection_admission`')]
#[ORM\Entity(repositoryClass: DataCrenasMoisProjectionAdmissionRepository::class)]
class DataCrenasMoisProjectionAdmission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'data_mois_admission_crenasannee_precedent')]
    private ?float $DataMoisAdmissionCRENASAnneePrecedent = null;

    #[ORM\Column(name: 'data_mois_admission_projete_annee_precedent')]
    private ?float $DataMoisAdmissionProjeteAnneePrecedent = null;

    #[ORM\Column(name: 'data_mois_projection_annee_previsionnelle')]
    private ?float $DataMoisProjectionAnneePrevisionnelle = null;

    #[ORM\ManyToOne(inversedBy: 'dataCrenasMoisProjectionAdmissions')]
    #[ORM\JoinColumn(nullable: true, name: 'data_crenas_id')]
    private ?DataCrenas $DataCrenas = null;

    #[ORM\ManyToOne(inversedBy: 'dataCrenasMoisProjectionAdmissions')]
    #[ORM\JoinColumn(nullable: true, name: 'mois_projections_admissions_id')]
    private ?MoisProjectionsAdmissions $MoisProjectionsAdmissions = null;

    #[ORM\ManyToOne(inversedBy: 'dataCrenasMoisProjectionAdmissions')]
    #[ORM\JoinColumn(nullable: true, name: 'moisPrevisionnelleEnclave_id')]
    private ?MoisPrevisionnelleEnclave $moisPrevisionnelleEnclave = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataMoisAdmissionCRENASAnneePrecedent(): ?float
    {
        return $this->DataMoisAdmissionCRENASAnneePrecedent;
    }

    public function setDataMoisAdmissionCRENASAnneePrecedent(float $DataMoisAdmissionCRENASAnneePrecedent): static
    {
        $this->DataMoisAdmissionCRENASAnneePrecedent = $DataMoisAdmissionCRENASAnneePrecedent;

        return $this;
    }

    public function getDataMoisAdmissionProjeteAnneePrecedent(): ?float
    {
        return $this->DataMoisAdmissionProjeteAnneePrecedent;
    }

    public function setDataMoisAdmissionProjeteAnneePrecedent(float $DataMoisAdmissionProjeteAnneePrecedent): static
    {
        $this->DataMoisAdmissionProjeteAnneePrecedent = $DataMoisAdmissionProjeteAnneePrecedent;

        return $this;
    }

    public function getDataMoisProjectionAnneePrevisionnelle(): ?float
    {
        return $this->DataMoisProjectionAnneePrevisionnelle;
    }

    public function setDataMoisProjectionAnneePrevisionnelle(float $DataMoisProjectionAnneePrevisionnelle): static
    {
        $this->DataMoisProjectionAnneePrevisionnelle = $DataMoisProjectionAnneePrevisionnelle;

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

    public function getMoisPrevisionnelleEnclave(): ?MoisPrevisionnelleEnclave
    {
        return $this->moisPrevisionnelleEnclave;
    }

    public function setMoisPrevisionnelleEnclave(?MoisPrevisionnelleEnclave $moisPrevisionnelleEnclave): static
    {
        $this->moisPrevisionnelleEnclave = $moisPrevisionnelleEnclave;

        return $this;
    }

}
