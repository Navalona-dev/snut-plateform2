<?php

namespace App\Entity;

use App\Repository\DataCrenasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`data_crenas`')]
#[ORM\Entity(repositoryClass: DataCrenasRepository::class)]
class DataCrenas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'besoin_apte')]
    private ?float $besoinAPTE = null;

    #[ORM\Column(name: 'besoin_amox')]
    private ?float $besoinAMOX = null;

    #[ORM\Column(name: 'besoin_fiche_patient')]
    private ?float $besoinFichePatient = null;

    #[ORM\Column(name: 'besoin_registre')]
    private ?float $besoinRegistre = null;

    #[ORM\Column(name: 'besoin_carnet_rapport_crenas')]
    private ?float $besoinCarnetRapportCRENAS = null;

    #[ORM\Column(name: 'quantite01_amox_sducarton_bsd')]
    private ?float $quantite01AmoxSDUCartonBSD = null;

    #[ORM\Column(length: 50, name: 'date_expiration01_amox_sducarton_bsd')]
    private ?string $dateExpiration01AmoxSDUCartonBSD = null;

    #[ORM\Column(nullable: true, name: 'quantite02_amox_sducarton_bsd')]
    private ?float $quantite02AmoxSDUCartonBSD = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration02_amox_sducarton_bsd')]
    private ?string $dateExpiration02AmoxSDUCartonBSD = null;

    #[ORM\Column(nullable: true, name: 'quantite03_amox_sducarton_bsd')]
    private ?float $quantite03AmoxSDUCartonBSD = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration03_amox_sducarton_bsd')]
    private ?string $dateExpiration03AmoxSDUCartonBSD = null;

    #[ORM\Column(nullable: true, name: 'quantite04_amox_sducarton_bsd')]
    private ?float $quantite04AmoxSDUCartonBSD = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration04_amox_sducarton_bsd')]
    private ?string $dateExpiration04AmoxSDUCartonBSD = null;

    #[ORM\Column(name: 'total_amox_sducarton_bsd')]
    private ?float $totalAmoxSDUCartonBSD = null;

    #[ORM\Column(name: 'quantite01_amox_sducarton_csb')]
    private ?float $quantite01AmoxSDUCartonCSB = null;

    #[ORM\Column(length: 50, name: 'date_expiration01_amox_sducarton_csb')]
    private ?string $dateExpiration01AmoxSDUCartonCSB = null;

    #[ORM\Column(nullable: true, name: 'quantite02_amox_sducarton_csb')]
    private ?float $quantite02AmoxSDUCartonCSB = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration02_amox_sducarton_csb')]
    private ?string $dateExpiration02AmoxSDUCartonCSB = null;

    #[ORM\Column(nullable: true, name: 'quantite03_amox_sducarton_csb')]
    private ?float $quantite03AmoxSDUCartonCSB = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration03_amox_sducarton_csb')]
    private ?string $dateExpiration03AmoxSDUCartonCSB = null;

    #[ORM\Column(nullable: true, name: 'quantite04_amox_sducarton_csb')]
    private ?float $quantite04AmoxSDUCartonCSB = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration04_amox_sducarton_csb')]
    private ?string $dateExpiration04AmoxSDUCartonCSB = null;

    #[ORM\Column(name: 'total_amox_sducarton_csb')]
    private ?float $totalAmoxSDUCartonCSB = null;

    #[ORM\Column(name: 'total_amox_sducarton_sdsp')]
    private ?float $totalAmoxSDUCartonSDSP = null;

    #[ORM\Column(name: 'quantite01_pn_sducarton_bsd')]
    private ?float $quantite01PnSDUCartonBSD = null;

    #[ORM\Column(length: 50, name: 'date_expiration01_pn_sducarton_bsd')]
    private ?string $dateExpiration01PnSDUCartonBSD = null;

    #[ORM\Column(nullable: true, name: 'quantite02_pn_sducarton_bsd')]
    private ?float $quantite02PnSDUCartonBSD = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration02_pn_sducarton_bsd')]
    private ?string $dateExpiration02PnSDUCartonBSD = null;

    #[ORM\Column(nullable: true, name: 'quantite03_pn_sducarton_bsd')]
    private ?float $quantite03PnSDUCartonBSD = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration03_pn_sducarton_bsd')]
    private ?string $dateExpiration03PnSDUCartonBSD = null;

    #[ORM\Column(nullable: true, name: 'quantite04_pn_sducarton_bsd')]
    private ?float $quantite04PnSDUCartonBSD = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration04_pn_sducarton_bsd')]
    private ?string $dateExpiration04PnSDUCartonBSD = null;

    #[ORM\Column(name: 'quantite01_pn_sducarton_csb')]
    private ?float $quantite01PnSDUCartonCSB = null;

    #[ORM\Column(length: 50, name: 'date_expiration01_pn_sducarton_csb')]
    private ?string $dateExpiration01PnSDUCartonCSB = null;

    #[ORM\Column(nullable: true, name: 'quantite02_pn_sducarton_csb')]
    private ?float $quantite02PnSDUCartonCSB = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration02_pn_sducarton_csb')]
    private ?string $dateExpiration02PnSDUCartonCSB = null;

    #[ORM\Column(nullable: true, name: 'quantite03_pn_sducarton_csb')]
    private ?float $quantite03PnSDUCartonCSB = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration03_pn_sducarton_csb')]
    private ?string $dateExpiration03PnSDUCartonCSB = null;

    #[ORM\Column(nullable: true, name: 'quantite04_pn_sducarton_csb')]
    private ?float $quantite04PnSDUCartonCSB = null;

    #[ORM\Column(length: 50, nullable: true, name: 'date_expiration04_pn_sducarton_csb')]
    private ?string $dateExpiration04PnSDUCartonCSB = null;

    #[ORM\Column(name: 'total_pn_sducarton_bsd')]
    private ?float $totalPnSDUCartonBSD = null;

    #[ORM\Column(name: 'total_pn_sducarton_csb')]
    private ?float $totalPnSDUCartonCSB = null;

    #[ORM\Column(name: 'total_pn_sducarton_sdsp')]
    private ?float $totalPnSDUCartonSDSP = null;

    #[ORM\Column(name: 'sdu_fiche')]
    private ?float $sduFiche = null;

    #[ORM\Column(name: 'nbr_csbcrenas')]
    private ?int $nbrCSBCRENAS = null;

    #[ORM\Column(name: 'taux_couverture_crenas')]
    private ?float $tauxCouvertureCRENAS = null;

    #[ORM\Column(name: 'nbr_csbcrenascommande')]
    private ?int $nbrCSBCRENASCommande = null;

    #[ORM\Column(name: 'taux_envoi_commande_csbcrenas')]
    private ?float $tauxEnvoiCommandeCSBCRENAS = null;

    #[ORM\ManyToOne(inversedBy: 'dataCrenas')]
    #[ORM\JoinColumn(name: 'user_id')]
    private ?User $User = null;

    #[ORM\OneToMany(mappedBy: 'DataCrenas', targetEntity: DataCrenasMoisProjectionAdmission::class)]
    private Collection $dataCrenasMoisProjectionAdmissions;

    #[ORM\Column(name: 'total_crenasannee_precedent')]
    private ?float $totalCRENASAnneePrecedent = null;

    #[ORM\Column(name: 'total_projete_annee_precedent')]
    private ?float $totalProjeteAnneePrecedent = null;

    #[ORM\Column(name: 'total_annee_projection')]
    private ?float $totalAnneeProjection = null;

    #[ORM\Column(name: 'resultat_annee_projection')]
    private ?float $resultatAnneeProjection = null;

    #[ORM\Column(name: 'resultat_annee_precedent')]
    private ?float $resultatAnneePrecedent = null;

    #[ORM\Column(name: 'nbr_total_csb')]
    private ?float $nbrTotalCsb = null;

    #[ORM\OneToMany(mappedBy: 'DataCrenas', targetEntity: DataValidationCrenas::class)]
    private Collection $dataValidationCrenas;

    #[ORM\ManyToOne(inversedBy: 'dataCrenas')]
    #[ORM\JoinColumn(name: 'groupe_id')]
    private ?Groupe $groupe = null;

    public function __construct()
    {
        $this->dataCrenasMoisProjectionAdmissions = new ArrayCollection();
        $this->dataValidationCrenas = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBesoinAPTE(): ?float
    {
        return $this->besoinAPTE;
    }

    public function setBesoinAPTE(float $besoinAPTE): static
    {
        $this->besoinAPTE = $besoinAPTE;

        return $this;
    }

    public function getBesoinAMOX(): ?float
    {
        return $this->besoinAMOX;
    }

    public function setBesoinAMOX(float $besoinAMOX): static
    {
        $this->besoinAMOX = $besoinAMOX;

        return $this;
    }

    public function getBesoinFichePatient(): ?float
    {
        return $this->besoinFichePatient;
    }

    public function setBesoinFichePatient(float $besoinFichePatient): static
    {
        $this->besoinFichePatient = $besoinFichePatient;

        return $this;
    }

    public function getBesoinRegistre(): ?float
    {
        return $this->besoinRegistre;
    }

    public function setBesoinRegistre(float $besoinRegistre): static
    {
        $this->besoinRegistre = $besoinRegistre;

        return $this;
    }

    public function getBesoinCarnetRapportCRENAS(): ?float
    {
        return $this->besoinCarnetRapportCRENAS;
    }

    public function setBesoinCarnetRapportCRENAS(float $besoinCarnetRapportCRENAS): static
    {
        $this->besoinCarnetRapportCRENAS = $besoinCarnetRapportCRENAS;

        return $this;
    }

    public function getQuantite01AmoxSDUCartonBSD(): ?float
    {
        return $this->quantite01AmoxSDUCartonBSD;
    }

    public function setQuantite01AmoxSDUCartonBSD(float $quantite01AmoxSDUCartonBSD): static
    {
        $this->quantite01AmoxSDUCartonBSD = $quantite01AmoxSDUCartonBSD;

        return $this;
    }

    public function getDateExpiration01AmoxSDUCartonBSD(): ?string
    {
        return $this->dateExpiration01AmoxSDUCartonBSD;
    }

    public function setDateExpiration01AmoxSDUCartonBSD(string $dateExpiration01AmoxSDUCartonBSD): static
    {
        $this->dateExpiration01AmoxSDUCartonBSD = $dateExpiration01AmoxSDUCartonBSD;

        return $this;
    }

    public function getQuantite02AmoxSDUCartonBSD(): ?float
    {
        return $this->quantite02AmoxSDUCartonBSD;
    }

    public function setQuantite02AmoxSDUCartonBSD(?float $quantite02AmoxSDUCartonBSD): static
    {
        $this->quantite02AmoxSDUCartonBSD = $quantite02AmoxSDUCartonBSD;

        return $this;
    }

    public function getDateExpiration02AmoxSDUCartonBSD(): ?string
    {
        return $this->dateExpiration02AmoxSDUCartonBSD;
    }

    public function setDateExpiration02AmoxSDUCartonBSD(?string $dateExpiration02AmoxSDUCartonBSD): static
    {
        $this->dateExpiration02AmoxSDUCartonBSD = $dateExpiration02AmoxSDUCartonBSD;

        return $this;
    }

    public function getQuantite03AmoxSDUCartonBSD(): ?float
    {
        return $this->quantite03AmoxSDUCartonBSD;
    }

    public function setQuantite03AmoxSDUCartonBSD(?float $quantite03AmoxSDUCartonBSD): static
    {
        $this->quantite03AmoxSDUCartonBSD = $quantite03AmoxSDUCartonBSD;

        return $this;
    }

    public function getDateExpiration03AmoxSDUCartonBSD(): ?string
    {
        return $this->dateExpiration03AmoxSDUCartonBSD;
    }

    public function setDateExpiration03AmoxSDUCartonBSD(?string $dateExpiration03AmoxSDUCartonBSD): static
    {
        $this->dateExpiration03AmoxSDUCartonBSD = $dateExpiration03AmoxSDUCartonBSD;

        return $this;
    }

    public function getQuantite04AmoxSDUCartonBSD(): ?float
    {
        return $this->quantite04AmoxSDUCartonBSD;
    }

    public function setQuantite04AmoxSDUCartonBSD(?float $quantite04AmoxSDUCartonBSD): static
    {
        $this->quantite04AmoxSDUCartonBSD = $quantite04AmoxSDUCartonBSD;

        return $this;
    }

    public function getDateExpiration04AmoxSDUCartonBSD(): ?string
    {
        return $this->dateExpiration04AmoxSDUCartonBSD;
    }

    public function setDateExpiration04AmoxSDUCartonBSD(string $dateExpiration04AmoxSDUCartonBSD): static
    {
        $this->dateExpiration04AmoxSDUCartonBSD = $dateExpiration04AmoxSDUCartonBSD;

        return $this;
    }

    public function getTotalAmoxSDUCartonBSD(): ?float
    {
        return $this->totalAmoxSDUCartonBSD;
    }

    public function setTotalAmoxSDUCartonBSD(float $totalAmoxSDUCartonBSD): static
    {
        $this->totalAmoxSDUCartonBSD = $totalAmoxSDUCartonBSD;

        return $this;
    }

    public function getQuantite01AmoxSDUCartonCSB(): ?float
    {
        return $this->quantite01AmoxSDUCartonCSB;
    }

    public function setQuantite01AmoxSDUCartonCSB(float $quantite01AmoxSDUCartonCSB): static
    {
        $this->quantite01AmoxSDUCartonCSB = $quantite01AmoxSDUCartonCSB;

        return $this;
    }

    public function getDateExpiration01AmoxSDUCartonCSB(): ?string
    {
        return $this->dateExpiration01AmoxSDUCartonCSB;
    }

    public function setDateExpiration01AmoxSDUCartonCSB(string $dateExpiration01AmoxSDUCartonCSB): static
    {
        $this->dateExpiration01AmoxSDUCartonCSB = $dateExpiration01AmoxSDUCartonCSB;

        return $this;
    }

    public function getQuantite02AmoxSDUCartonCSB(): ?float
    {
        return $this->quantite02AmoxSDUCartonCSB;
    }

    public function setQuantite02AmoxSDUCartonCSB(?float $quantite02AmoxSDUCartonCSB): static
    {
        $this->quantite02AmoxSDUCartonCSB = $quantite02AmoxSDUCartonCSB;

        return $this;
    }

    public function getDateExpiration02AmoxSDUCartonCSB(): ?string
    {
        return $this->dateExpiration02AmoxSDUCartonCSB;
    }

    public function setDateExpiration02AmoxSDUCartonCSB(?string $dateExpiration02AmoxSDUCartonCSB): static
    {
        $this->dateExpiration02AmoxSDUCartonCSB = $dateExpiration02AmoxSDUCartonCSB;

        return $this;
    }

    public function getQuantite03AmoxSDUCartonCSB(): ?float
    {
        return $this->quantite03AmoxSDUCartonCSB;
    }

    public function setQuantite03AmoxSDUCartonCSB(?float $quantite03AmoxSDUCartonCSB): static
    {
        $this->quantite03AmoxSDUCartonCSB = $quantite03AmoxSDUCartonCSB;

        return $this;
    }

    public function getDateExpiration03AmoxSDUCartonCSB(): ?string
    {
        return $this->dateExpiration03AmoxSDUCartonCSB;
    }

    public function setDateExpiration03AmoxSDUCartonCSB(?string $dateExpiration03AmoxSDUCartonCSB): static
    {
        $this->dateExpiration03AmoxSDUCartonCSB = $dateExpiration03AmoxSDUCartonCSB;

        return $this;
    }

    public function getQuantite04AmoxSDUCartonCSB(): ?float
    {
        return $this->quantite04AmoxSDUCartonCSB;
    }

    public function setQuantite04AmoxSDUCartonCSB(?float $quantite04AmoxSDUCartonCSB): static
    {
        $this->quantite04AmoxSDUCartonCSB = $quantite04AmoxSDUCartonCSB;

        return $this;
    }

    public function getDateExpiration04AmoxSDUCartonCSB(): ?string
    {
        return $this->dateExpiration04AmoxSDUCartonCSB;
    }

    public function setDateExpiration04AmoxSDUCartonCSB(?string $dateExpiration04AmoxSDUCartonCSB): static
    {
        $this->dateExpiration04AmoxSDUCartonCSB = $dateExpiration04AmoxSDUCartonCSB;

        return $this;
    }

    public function getTotalAmoxSDUCartonCSB(): ?float
    {
        return $this->totalAmoxSDUCartonCSB;
    }

    public function setTotalAmoxSDUCartonCSB(float $totalAmoxSDUCartonCSB): static
    {
        $this->totalAmoxSDUCartonCSB = $totalAmoxSDUCartonCSB;

        return $this;
    }

    public function getTotalAmoxSDUCartonSDSP(): ?float
    {
        return $this->totalAmoxSDUCartonSDSP;
    }

    public function setTotalAmoxSDUCartonSDSP(float $totalAmoxSDUCartonSDSP): static
    {
        $this->totalAmoxSDUCartonSDSP = $totalAmoxSDUCartonSDSP;

        return $this;
    }

    public function getQuantite01PnSDUCartonBSD(): ?float
    {
        return $this->quantite01PnSDUCartonBSD;
    }

    public function setQuantite01PnSDUCartonBSD(float $quantite01PnSDUCartonBSD): static
    {
        $this->quantite01PnSDUCartonBSD = $quantite01PnSDUCartonBSD;

        return $this;
    }

    public function getDateExpiration01PnSDUCartonBSD(): ?string
    {
        return $this->dateExpiration01PnSDUCartonBSD;
    }

    public function setDateExpiration01PnSDUCartonBSD(string $dateExpiration01PnSDUCartonBSD): static
    {
        $this->dateExpiration01PnSDUCartonBSD = $dateExpiration01PnSDUCartonBSD;

        return $this;
    }

    public function getQuantite02PnSDUCartonBSD(): ?float
    {
        return $this->quantite02PnSDUCartonBSD;
    }

    public function setQuantite02PnSDUCartonBSD(?float $quantite02PnSDUCartonBSD): static
    {
        $this->quantite02PnSDUCartonBSD = $quantite02PnSDUCartonBSD;

        return $this;
    }

    public function getDateExpiration02PnSDUCartonBSD(): ?string
    {
        return $this->dateExpiration02PnSDUCartonBSD;
    }

    public function setDateExpiration02PnSDUCartonBSD(?string $dateExpiration02PnSDUCartonBSD): static
    {
        $this->dateExpiration02PnSDUCartonBSD = $dateExpiration02PnSDUCartonBSD;

        return $this;
    }

    public function getQuantite03PnSDUCartonBSD(): ?float
    {
        return $this->quantite03PnSDUCartonBSD;
    }

    public function setQuantite03PnSDUCartonBSD(?float $quantite03PnSDUCartonBSD): static
    {
        $this->quantite03PnSDUCartonBSD = $quantite03PnSDUCartonBSD;

        return $this;
    }

    public function getDateExpiration03PnSDUCartonBSD(): ?string
    {
        return $this->dateExpiration03PnSDUCartonBSD;
    }

    public function setDateExpiration03PnSDUCartonBSD(?string $dateExpiration03PnSDUCartonBSD): static
    {
        $this->dateExpiration03PnSDUCartonBSD = $dateExpiration03PnSDUCartonBSD;

        return $this;
    }

    public function getQuantite04PnSDUCartonBSD(): ?float
    {
        return $this->quantite04PnSDUCartonBSD;
    }

    public function setQuantite04PnSDUCartonBSD(?float $quantite04PnSDUCartonBSD): static
    {
        $this->quantite04PnSDUCartonBSD = $quantite04PnSDUCartonBSD;

        return $this;
    }

    public function getDateExpiration04PnSDUCartonBSD(): ?string
    {
        return $this->dateExpiration04PnSDUCartonBSD;
    }

    public function setDateExpiration04PnSDUCartonBSD(?string $dateExpiration04PnSDUCartonBSD): static
    {
        $this->dateExpiration04PnSDUCartonBSD = $dateExpiration04PnSDUCartonBSD;

        return $this;
    }

    public function getQuantite01PnSDUCartonCSB(): ?float
    {
        return $this->quantite01PnSDUCartonCSB;
    }

    public function setQuantite01PnSDUCartonCSB(float $quantite01PnSDUCartonCSB): static
    {
        $this->quantite01PnSDUCartonCSB = $quantite01PnSDUCartonCSB;

        return $this;
    }

    public function getDateExpiration01PnSDUCartonCSB(): ?string
    {
        return $this->dateExpiration01PnSDUCartonCSB;
    }

    public function setDateExpiration01PnSDUCartonCSB(string $dateExpiration01PnSDUCartonCSB): static
    {
        $this->dateExpiration01PnSDUCartonCSB = $dateExpiration01PnSDUCartonCSB;

        return $this;
    }

    public function getQuantite02PnSDUCartonCSB(): ?float
    {
        return $this->quantite02PnSDUCartonCSB;
    }

    public function setQuantite02PnSDUCartonCSB(?float $quantite02PnSDUCartonCSB): static
    {
        $this->quantite02PnSDUCartonCSB = $quantite02PnSDUCartonCSB;

        return $this;
    }

    public function getDateExpiration02PnSDUCartonCSB(): ?string
    {
        return $this->dateExpiration02PnSDUCartonCSB;
    }

    public function setDateExpiration02PnSDUCartonCSB(?string $dateExpiration02PnSDUCartonCSB): static
    {
        $this->dateExpiration02PnSDUCartonCSB = $dateExpiration02PnSDUCartonCSB;

        return $this;
    }

    public function getQuantite03PnSDUCartonCSB(): ?float
    {
        return $this->quantite03PnSDUCartonCSB;
    }

    public function setQuantite03PnSDUCartonCSB(?float $quantite03PnSDUCartonCSB): static
    {
        $this->quantite03PnSDUCartonCSB = $quantite03PnSDUCartonCSB;

        return $this;
    }

    public function getDateExpiration03PnSDUCartonCSB(): ?string
    {
        return $this->dateExpiration03PnSDUCartonCSB;
    }

    public function setDateExpiration03PnSDUCartonCSB(?string $dateExpiration03PnSDUCartonCSB): static
    {
        $this->dateExpiration03PnSDUCartonCSB = $dateExpiration03PnSDUCartonCSB;

        return $this;
    }

    public function getQuantite04PnSDUCartonCSB(): ?float
    {
        return $this->quantite04PnSDUCartonCSB;
    }

    public function setQuantite04PnSDUCartonCSB(?float $quantite04PnSDUCartonCSB): static
    {
        $this->quantite04PnSDUCartonCSB = $quantite04PnSDUCartonCSB;

        return $this;
    }

    public function getDateExpiration04PnSDUCartonCSB(): ?string
    {
        return $this->dateExpiration04PnSDUCartonCSB;
    }

    public function setDateExpiration04PnSDUCartonCSB(?string $dateExpiration04PnSDUCartonCSB): static
    {
        $this->dateExpiration04PnSDUCartonCSB = $dateExpiration04PnSDUCartonCSB;

        return $this;
    }

    public function getTotalPnSDUCartonBSD(): ?float
    {
        return $this->totalPnSDUCartonBSD;
    }

    public function setTotalPnSDUCartonBSD(float $totalPnSDUCartonBSD): static
    {
        $this->totalPnSDUCartonBSD = $totalPnSDUCartonBSD;

        return $this;
    }

    public function getTotalPnSDUCartonCSB(): ?float
    {
        return $this->totalPnSDUCartonCSB;
    }

    public function setTotalPnSDUCartonCSB(float $totalPnSDUCartonCSB): static
    {
        $this->totalPnSDUCartonCSB = $totalPnSDUCartonCSB;

        return $this;
    }

    public function getTotalPnSDUCartonSDSP(): ?float
    {
        return $this->totalPnSDUCartonSDSP;
    }

    public function setTotalPnSDUCartonSDSP(float $totalPnSDUCartonSDSP): static
    {
        $this->totalPnSDUCartonSDSP = $totalPnSDUCartonSDSP;

        return $this;
    }

    public function getSduFiche(): ?float
    {
        return $this->sduFiche;
    }

    public function setSduFiche(float $sduFiche): static
    {
        $this->sduFiche = $sduFiche;

        return $this;
    }

    public function getNbrCSBCRENAS(): ?int
    {
        return $this->nbrCSBCRENAS;
    }

    public function setNbrCSBCRENAS(int $nbrCSBCRENAS): static
    {
        $this->nbrCSBCRENAS = $nbrCSBCRENAS;

        return $this;
    }

    public function getTauxCouvertureCRENAS(): ?float
    {
        return $this->tauxCouvertureCRENAS;
    }

    public function setTauxCouvertureCRENAS(float $tauxCouvertureCRENAS): static
    {
        $this->tauxCouvertureCRENAS = $tauxCouvertureCRENAS;

        return $this;
    }

    public function getNbrCSBCRENASCommande(): ?int
    {
        return $this->nbrCSBCRENASCommande;
    }

    public function setNbrCSBCRENASCommande(int $nbrCSBCRENASCommande): static
    {
        $this->nbrCSBCRENASCommande = $nbrCSBCRENASCommande;

        return $this;
    }

    public function getTauxEnvoiCommandeCSBCRENAS(): ?float
    {
        return $this->tauxEnvoiCommandeCSBCRENAS;
    }

    public function setTauxEnvoiCommandeCSBCRENAS(float $tauxEnvoiCommandeCSBCRENAS): static
    {
        $this->tauxEnvoiCommandeCSBCRENAS = $tauxEnvoiCommandeCSBCRENAS;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

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
            $dataCrenasMoisProjectionAdmission->setDataCrenas($this);
        }

        return $this;
    }

    public function removeDataCrenasMoisProjectionAdmission(DataCrenasMoisProjectionAdmission $dataCrenasMoisProjectionAdmission): static
    {
        if ($this->dataCrenasMoisProjectionAdmissions->removeElement($dataCrenasMoisProjectionAdmission)) {
            // set the owning side to null (unless already changed)
            if ($dataCrenasMoisProjectionAdmission->getDataCrenas() === $this) {
                $dataCrenasMoisProjectionAdmission->setDataCrenas(null);
            }
        }

        return $this;
    }

    public function getTotalCRENASAnneePrecedent(): ?float
    {
        return $this->totalCRENASAnneePrecedent;
    }

    public function setTotalCRENASAnneePrecedent(float $totalCRENASAnneePrecedent): static
    {
        $this->totalCRENASAnneePrecedent = $totalCRENASAnneePrecedent;

        return $this;
    }

    public function getTotalProjeteAnneePrecedent(): ?float
    {
        return $this->totalProjeteAnneePrecedent;
    }

    public function setTotalProjeteAnneePrecedent(float $totalProjeteAnneePrecedent): static
    {
        $this->totalProjeteAnneePrecedent = $totalProjeteAnneePrecedent;

        return $this;
    }

    public function getTotalAnneeProjection(): ?float
    {
        return $this->totalAnneeProjection;
    }

    public function setTotalAnneeProjection(float $totalAnneeProjection): static
    {
        $this->totalAnneeProjection = $totalAnneeProjection;

        return $this;
    }

    public function getResultatAnneeProjection(): ?float
    {
        return $this->resultatAnneeProjection;
    }

    public function setResultatAnneeProjection(float $resultatAnneeProjection): static
    {
        $this->resultatAnneeProjection = $resultatAnneeProjection;

        return $this;
    }

    public function getResultatAnneePrecedent(): ?float
    {
        return $this->resultatAnneePrecedent;
    }

    public function setResultatAnneePrecedent(float $resultatAnneePrecedent): static
    {
        $this->resultatAnneePrecedent = $resultatAnneePrecedent;

        return $this;
    }

    public function getNbrTotalCsb(): ?float
    {
        return $this->nbrTotalCsb;
    }

    public function setNbrTotalCsb(float $nbrTotalCsb): static
    {
        $this->nbrTotalCsb = $nbrTotalCsb;

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
            $dataValidationCrena->setDataCrenas($this);
        }

        return $this;
    }

    public function removeDataValidationCrena(DataValidationCrenas $dataValidationCrena): static
    {
        if ($this->dataValidationCrenas->removeElement($dataValidationCrena)) {
            // set the owning side to null (unless already changed)
            if ($dataValidationCrena->getDataCrenas() === $this) {
                $dataValidationCrena->setDataCrenas(null);
            }
        }

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }
}
