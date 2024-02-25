<?php

namespace App\Entity;

use App\Repository\DataCreniRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`data_creni`')]
#[ORM\Entity(repositoryClass: DataCreniRepository::class)]
class DataCreni
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'total_admission_creni_semestre_precedent')]
    private ?float $totalAdmissionCreniSemestrePrecedent = null;

    #[ORM\Column(name: 'total_admission_creni_projete_precedent')]
    private ?float $totalAdmissionCreniProjetePrecedent = null;

    #[ORM\Column(name: 'resultat_difference_admission_precedent')]
    private ?float $ResultatDifferenceAdmissionPrecedent = null;

    #[ORM\Column(name: 'total_admission_creni_projeter_prochain')]
    private ?float $totalAdmissionCreniProjeterProchain = null;

    #[ORM\Column(name: 'resultat_difference_admission_prochain_precedent')]
    private ?float $ResultatDifferenceAdmissionProchainPrecedent = null;

    #[ORM\Column(name: 'f75_boites')]
    private ?float $f75Boites = null;

    #[ORM\Column(name: 'f100_boites')]
    private ?float $f100Boites = null;

    #[ORM\Column(name: 're_so_mal_sachet')]
    private ?float $ReSoMalSachet = null;

    #[ORM\Column(name: 'pn_sachet')]
    private ?float $pnSachet = null;

    #[ORM\Column(name: 'fiche_suivi_creni')]
    private ?float $ficheSuiviCreni = null;

    #[ORM\Column(name: 'fiche_suivi_intensif')]
    private ?float $ficheSuiviIntensif = null;

    #[ORM\Column(name: 'kit_medicaments_creni10_patients')]
    private ?float $kitMedicamentsCreni10Patients = null;

    #[ORM\OneToMany(mappedBy: 'DataCreni', targetEntity: DataCreniMoisProjectionAdmission::class)]
    private Collection $dataCreniMoisProjectionAdmissions;

    #[ORM\ManyToOne(inversedBy: 'dataCrenis')]
    #[ORM\JoinColumn(nullable: true, name: 'user_id')]
    private ?User $User = null;

    #[ORM\Column(name: ' registre_creni ')]
    private ?float $registreCreni = null;

    #[ORM\Column(name: 'carnet_rapport_mensuel_creni')]
    private ?float $carnetRapportMensuelCreni = null;

    #[ORM\Column(name: 'kit_creni_amoxici')]
    private ?float $kitCreniAmoxici = null;

    #[ORM\Column(name: 'kit_creni_nystatin')]
    private ?float $kitCreniNystatin = null;

    #[ORM\Column(name: 'kit_creni_fluconazole')]
    private ?float $kitCreniFluconazole = null;

    #[ORM\Column(name: 'kit_creni_ciprofloxacin')]
    private ?float $kitCreniCiprofloxacin = null;

    #[ORM\Column(name: 'kit_creni_ampicillinpdr')]
    private ?float $kitCreniAmpicillinpdr = null;

    #[ORM\Column(name: 'kit_creni_gentamicininj')]
    private ?float $kitCreniGentamicininj = null;

    #[ORM\Column(name: 'kit_creni_sod')]
    private ?float $kitCreniSod = null;

    #[ORM\Column(name: 'kit_creni_glucose_inj')]
    private ?float $kitCreniGlucoseInj = null;

    #[ORM\Column(name: 'kit_creni_glucose_hyperton_inj')]
    private ?float $kitCreniGlucoseHypertonInj = null;

    #[ORM\Column(name: 'kit_creni_furosemideinj')]
    private ?float $kitCreniFurosemideinj = null;

    #[ORM\Column(name: 'kit_creni_chlorhexidine')]
    private ?float $kitCreniChlorhexidine = null;

    #[ORM\Column(name: 'kit_creni_miconazole')]
    private ?float $kitCreniMiconazole = null;

    #[ORM\Column(name: 'kit_creni_tetracyclineeyeointment')]
    private ?float $kitCreniTetracyclineeyeointment = null;

    #[ORM\Column(name: 'kit_creni_tube_feeding')]
    private ?float $kitCreniTubeFeeding = null;

    #[ORM\Column(name: 'kit_creni_tube_feeding_ch05')]
    private ?float $kitCreniTubeFeedingCH05 = null;

    #[ORM\Column(name: 'kit_creni_syringe_disp2ml')]
    private ?float $kitCreniSyringeDisp2ml = null;

    #[ORM\Column(name: 'kit_creni_syringe_disp10ml')]
    private ?float $kitCreniSyringeDisp10ml = null;

    #[ORM\Column(name: 'kit_creni_syringe_disp20ml')]
    private ?float $kitCreniSyringeDisp20ml = null;

    #[ORM\Column(name: 'kit_creni_syringe_disp50ml')]
    private ?float $kitCreniSyringeDisp50ml = null;

    #[ORM\Column(name: 'sdu_f75_boites')]
    private ?float $sduF75Boites = null;

    #[ORM\Column(name: 'sdu_f100_boites')]
    private ?float $sduF100Boites = null;

    #[ORM\Column(name: 'sdu_re_so_mal')]
    private ?float $sduReSoMal = null;

    #[ORM\Column(name: 'sdu_pn_sachet')]
    private ?float $sduPnSachet = null;

    #[ORM\Column(name: 'sdu_fiche_suivi_creni')]
    private ?float $sduFicheSuiviCreni = null;

    #[ORM\Column(name: 'sdu_fiche_suivi_intensif')]
    private ?float $sduFicheSuiviIntensif = null;

    #[ORM\Column(name: 'sdu_amoxici_pdr')]
    private ?float $sduAmoxiciPdr = null;

    #[ORM\Column(name: 'sdu_nystatin_oral')]
    private ?float $sduNystatinOral = null;

    #[ORM\Column(name: 'sdu_fluconazole50mg')]
    private ?float $sduFluconazole50mg = null;

    #[ORM\Column(name: 'sdu_ampicillinpdr_inj500mg')]
    private ?float $sduAmpicillinpdrInj500mg = null;

    #[ORM\Column(name: 'sdu_gentamicininj40mg')]
    private ?float $sduGentamicininj40mg = null;

    #[ORM\Column(name: 'sdu_sod_lactat_inj500ml')]
    private ?float $sduSodLactatInj500ml = null;

    #[ORM\Column(name: 'sdu_glucose_inj500ml')]
    private ?float $sduGlucoseInj500ml = null;

    #[ORM\Column(name: 'sdu_glucose_hyperton50ml')]
    private ?float $sduGlucoseHyperton50ml = null;

    #[ORM\Column(name: 'sdu_furosemideinj10mg')]
    private ?float $sduFurosemideinj10mg = null;

    #[ORM\Column(name: 'sdu_chlorhexidine_con_sol')]
    private ?float $sduChlorhexidineConSol = null;

    #[ORM\Column(name: 'sdu_miconazole_nitrate')]
    private ?float $sduMiconazoleNitrate = null;

    #[ORM\Column(name: 'sdu_tetracyclineeyeointment')]
    private ?float $sduTetracyclineeyeointment = null;

    #[ORM\Column(name: 'sdu_tube_feeding_ch08')]
    private ?float $sduTubeFeedingCH08 = null;

    #[ORM\Column(name: 'sdu_tube_feeding_ch05')]
    private ?float $sduTubeFeedingCH05 = null;

    #[ORM\Column(name: 'sdu_syringe_disp2ml')]
    private ?float $sduSyringeDisp2ml = null;

    #[ORM\Column(name: 'sdu_syringe_disp10ml')]
    private ?float $sduSyringeDisp10ml = null;

    #[ORM\Column(name: 'sdu_syringe_disp20ml')]
    private ?float $sduSyringeDisp20ml = null;

    #[ORM\Column(name: 'sdu_syringe_feeding50ml')]
    private ?float $sduSyringeFeeding50ml = null;

    #[ORM\Column(name: 'sdu_ciprofloxacin250mg')]
    private ?float $sduCiprofloxacin250mg = null;

    #[ORM\OneToMany(mappedBy: 'DataCreni', targetEntity: DataValidationCreni::class)]
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

    public function getTotalAdmissionCreniSemestrePrecedent(): ?float
    {
        return $this->totalAdmissionCreniSemestrePrecedent;
    }

    public function setTotalAdmissionCreniSemestrePrecedent(float $totalAdmissionCreniSemestrePrecedent): static
    {
        $this->totalAdmissionCreniSemestrePrecedent = $totalAdmissionCreniSemestrePrecedent;

        return $this;
    }

    public function getTotalAdmissionCreniProjetePrecedent(): ?float
    {
        return $this->totalAdmissionCreniProjetePrecedent;
    }

    public function setTotalAdmissionCreniProjetePrecedent(float $totalAdmissionCreniProjetePrecedent): static
    {
        $this->totalAdmissionCreniProjetePrecedent = $totalAdmissionCreniProjetePrecedent;

        return $this;
    }

    public function getResultatDifferenceAdmissionPrecedent(): ?float
    {
        return $this->ResultatDifferenceAdmissionPrecedent;
    }

    public function setResultatDifferenceAdmissionPrecedent(float $ResultatDifferenceAdmissionPrecedent): static
    {
        $this->ResultatDifferenceAdmissionPrecedent = $ResultatDifferenceAdmissionPrecedent;

        return $this;
    }

    public function getTotalAdmissionCreniProjeterProchain(): ?float
    {
        return $this->totalAdmissionCreniProjeterProchain;
    }

    public function setTotalAdmissionCreniProjeterProchain(float $totalAdmissionCreniProjeterProchain): static
    {
        $this->totalAdmissionCreniProjeterProchain = $totalAdmissionCreniProjeterProchain;

        return $this;
    }

    public function getResultatDifferenceAdmissionProchainPrecedent(): ?float
    {
        return $this->ResultatDifferenceAdmissionProchainPrecedent;
    }

    public function setResultatDifferenceAdmissionProchainPrecedent(float $ResultatDifferenceAdmissionProchainPrecedent): static
    {
        $this->ResultatDifferenceAdmissionProchainPrecedent = $ResultatDifferenceAdmissionProchainPrecedent;

        return $this;
    }

    public function getF75Boites(): ?float
    {
        return $this->f75Boites;
    }

    public function setF75Boites(float $f75Boites): static
    {
        $this->f75Boites = $f75Boites;

        return $this;
    }

    public function getF100Boites(): ?float
    {
        return $this->f100Boites;
    }

    public function setF100Boites(float $f100Boites): static
    {
        $this->f100Boites = $f100Boites;

        return $this;
    }

    public function getReSoMalSachet(): ?float
    {
        return $this->ReSoMalSachet;
    }

    public function setReSoMalSachet(float $ReSoMalSachet): static
    {
        $this->ReSoMalSachet = $ReSoMalSachet;

        return $this;
    }

    public function getPnSachet(): ?float
    {
        return $this->pnSachet;
    }

    public function setPnSachet(float $pnSachet): static
    {
        $this->pnSachet = $pnSachet;

        return $this;
    }

    public function getFicheSuiviCreni(): ?float
    {
        return $this->ficheSuiviCreni;
    }

    public function setFicheSuiviCreni(float $ficheSuiviCreni): static
    {
        $this->ficheSuiviCreni = $ficheSuiviCreni;

        return $this;
    }

    public function getFicheSuiviIntensif(): ?float
    {
        return $this->ficheSuiviIntensif;
    }

    public function setFicheSuiviIntensif(float $ficheSuiviIntensif): static
    {
        $this->ficheSuiviIntensif = $ficheSuiviIntensif;

        return $this;
    }

    public function getKitMedicamentsCreni10Patients(): ?float
    {
        return $this->kitMedicamentsCreni10Patients;
    }

    public function setKitMedicamentsCreni10Patients(float $kitMedicamentsCreni10Patients): static
    {
        $this->kitMedicamentsCreni10Patients = $kitMedicamentsCreni10Patients;

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
            $dataCreniMoisProjectionAdmission->setDataCreni($this);
        }

        return $this;
    }

    public function removeDataCreniMoisProjectionAdmission(DataCreniMoisProjectionAdmission $dataCreniMoisProjectionAdmission): static
    {
        if ($this->dataCreniMoisProjectionAdmissions->removeElement($dataCreniMoisProjectionAdmission)) {
            // set the owning side to null (unless already changed)
            if ($dataCreniMoisProjectionAdmission->getDataCreni() === $this) {
                $dataCreniMoisProjectionAdmission->setDataCreni(null);
            }
        }

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

    public function getRegistreCreni(): ?float
    {
        return $this->registreCreni;
    }

    public function setRegistreCreni(float $registreCreni): static
    {
        $this->registreCreni = $registreCreni;

        return $this;
    }

    public function getCarnetRapportMensuelCreni(): ?float
    {
        return $this->carnetRapportMensuelCreni;
    }

    public function setCarnetRapportMensuelCreni(float $carnetRapportMensuelCreni): static
    {
        $this->carnetRapportMensuelCreni = $carnetRapportMensuelCreni;

        return $this;
    }

    public function getKitCreniAmoxici(): ?float
    {
        return $this->kitCreniAmoxici;
    }

    public function setKitCreniAmoxici(float $kitCreniAmoxici): static
    {
        $this->kitCreniAmoxici = $kitCreniAmoxici;

        return $this;
    }

    public function getKitCreniNystatin(): ?float
    {
        return $this->kitCreniNystatin;
    }

    public function setKitCreniNystatin(float $kitCreniNystatin): static
    {
        $this->kitCreniNystatin = $kitCreniNystatin;

        return $this;
    }

    public function getKitCreniFluconazole(): ?float
    {
        return $this->kitCreniFluconazole;
    }

    public function setKitCreniFluconazole(float $kitCreniFluconazole): static
    {
        $this->kitCreniFluconazole = $kitCreniFluconazole;

        return $this;
    }

    public function getKitCreniCiprofloxacin(): ?float
    {
        return $this->kitCreniCiprofloxacin;
    }

    public function setKitCreniCiprofloxacin(float $kitCreniCiprofloxacin): static
    {
        $this->kitCreniCiprofloxacin = $kitCreniCiprofloxacin;

        return $this;
    }

    public function getKitCreniAmpicillinpdr(): ?float
    {
        return $this->kitCreniAmpicillinpdr;
    }

    public function setKitCreniAmpicillinpdr(float $kitCreniAmpicillinpdr): static
    {
        $this->kitCreniAmpicillinpdr = $kitCreniAmpicillinpdr;

        return $this;
    }

    public function getKitCreniGentamicininj(): ?float
    {
        return $this->kitCreniGentamicininj;
    }

    public function setKitCreniGentamicininj(float $kitCreniGentamicininj): static
    {
        $this->kitCreniGentamicininj = $kitCreniGentamicininj;

        return $this;
    }

    public function getKitCreniSod(): ?float
    {
        return $this->kitCreniSod;
    }

    public function setKitCreniSod(float $kitCreniSod): static
    {
        $this->kitCreniSod = $kitCreniSod;

        return $this;
    }

    public function getKitCreniGlucoseInj(): ?float
    {
        return $this->kitCreniGlucoseInj;
    }

    public function setKitCreniGlucoseInj(float $kitCreniGlucoseInj): static
    {
        $this->kitCreniGlucoseInj = $kitCreniGlucoseInj;

        return $this;
    }

    public function getKitCreniGlucoseHypertonInj(): ?float
    {
        return $this->kitCreniGlucoseHypertonInj;
    }

    public function setKitCreniGlucoseHypertonInj(float $kitCreniGlucoseHypertonInj): static
    {
        $this->kitCreniGlucoseHypertonInj = $kitCreniGlucoseHypertonInj;

        return $this;
    }

    public function getKitCreniFurosemideinj(): ?float
    {
        return $this->kitCreniFurosemideinj;
    }

    public function setKitCreniFurosemideinj(float $kitCreniFurosemideinj): static
    {
        $this->kitCreniFurosemideinj = $kitCreniFurosemideinj;

        return $this;
    }

    public function getKitCreniChlorhexidine(): ?float
    {
        return $this->kitCreniChlorhexidine;
    }

    public function setKitCreniChlorhexidine(float $kitCreniChlorhexidine): static
    {
        $this->kitCreniChlorhexidine = $kitCreniChlorhexidine;

        return $this;
    }

    public function getKitCreniMiconazole(): ?float
    {
        return $this->kitCreniMiconazole;
    }

    public function setKitCreniMiconazole(float $kitCreniMiconazole): static
    {
        $this->kitCreniMiconazole = $kitCreniMiconazole;

        return $this;
    }

    public function getKitCreniTetracyclineeyeointment(): ?float
    {
        return $this->kitCreniTetracyclineeyeointment;
    }

    public function setKitCreniTetracyclineeyeointment(float $kitCreniTetracyclineeyeointment): static
    {
        $this->kitCreniTetracyclineeyeointment = $kitCreniTetracyclineeyeointment;

        return $this;
    }

    public function getKitCreniTubeFeeding(): ?float
    {
        return $this->kitCreniTubeFeeding;
    }

    public function setKitCreniTubeFeeding(float $kitCreniTubeFeeding): static
    {
        $this->kitCreniTubeFeeding = $kitCreniTubeFeeding;

        return $this;
    }

    public function getKitCreniTubeFeedingCH05(): ?float
    {
        return $this->kitCreniTubeFeedingCH05;
    }

    public function setKitCreniTubeFeedingCH05(float $kitCreniTubeFeedingCH05): static
    {
        $this->kitCreniTubeFeedingCH05 = $kitCreniTubeFeedingCH05;

        return $this;
    }

    public function getKitCreniSyringeDisp2ml(): ?float
    {
        return $this->kitCreniSyringeDisp2ml;
    }

    public function setKitCreniSyringeDisp2ml(float $kitCreniSyringeDisp2ml): static
    {
        $this->kitCreniSyringeDisp2ml = $kitCreniSyringeDisp2ml;

        return $this;
    }

    public function getKitCreniSyringeDisp10ml(): ?float
    {
        return $this->kitCreniSyringeDisp10ml;
    }

    public function setKitCreniSyringeDisp10ml(float $kitCreniSyringeDisp10ml): static
    {
        $this->kitCreniSyringeDisp10ml = $kitCreniSyringeDisp10ml;

        return $this;
    }

    public function getKitCreniSyringeDisp20ml(): ?float
    {
        return $this->kitCreniSyringeDisp20ml;
    }

    public function setKitCreniSyringeDisp20ml(float $kitCreniSyringeDisp20ml): static
    {
        $this->kitCreniSyringeDisp20ml = $kitCreniSyringeDisp20ml;

        return $this;
    }

    public function getKitCreniSyringeDisp50ml(): ?float
    {
        return $this->kitCreniSyringeDisp50ml;
    }

    public function setKitCreniSyringeDisp50ml(float $kitCreniSyringeDisp50ml): static
    {
        $this->kitCreniSyringeDisp50ml = $kitCreniSyringeDisp50ml;

        return $this;
    }

    public function getSduF75Boites(): ?float
    {
        return $this->sduF75Boites;
    }

    public function setSduF75Boites(float $sduF75Boites): static
    {
        $this->sduF75Boites = $sduF75Boites;

        return $this;
    }

    public function getSduF100Boites(): ?float
    {
        return $this->sduF100Boites;
    }

    public function setSduF100Boites(float $sduF100Boites): static
    {
        $this->sduF100Boites = $sduF100Boites;

        return $this;
    }

    public function getSduReSoMal(): ?float
    {
        return $this->sduReSoMal;
    }

    public function setSduReSoMal(float $sduReSoMal): static
    {
        $this->sduReSoMal = $sduReSoMal;

        return $this;
    }

    public function getSduPnSachet(): ?float
    {
        return $this->sduPnSachet;
    }

    public function setSduPnSachet(float $sduPnSachet): static
    {
        $this->sduPnSachet = $sduPnSachet;

        return $this;
    }

    public function getSduFicheSuiviCreni(): ?float
    {
        return $this->sduFicheSuiviCreni;
    }

    public function setSduFicheSuiviCreni(float $sduFicheSuiviCreni): static
    {
        $this->sduFicheSuiviCreni = $sduFicheSuiviCreni;

        return $this;
    }

    public function getSduFicheSuiviIntensif(): ?float
    {
        return $this->sduFicheSuiviIntensif;
    }

    public function setSduFicheSuiviIntensif(float $sduFicheSuiviIntensif): static
    {
        $this->sduFicheSuiviIntensif = $sduFicheSuiviIntensif;

        return $this;
    }

    public function getSduAmoxiciPdr(): ?float
    {
        return $this->sduAmoxiciPdr;
    }

    public function setSduAmoxiciPdr(float $sduAmoxiciPdr): static
    {
        $this->sduAmoxiciPdr = $sduAmoxiciPdr;

        return $this;
    }

    public function getSduNystatinOral(): ?float
    {
        return $this->sduNystatinOral;
    }

    public function setSduNystatinOral(float $sduNystatinOral): static
    {
        $this->sduNystatinOral = $sduNystatinOral;

        return $this;
    }

    public function getSduFluconazole50mg(): ?float
    {
        return $this->sduFluconazole50mg;
    }

    public function setSduFluconazole50mg(float $sduFluconazole50mg): static
    {
        $this->sduFluconazole50mg = $sduFluconazole50mg;

        return $this;
    }

    public function getSduAmpicillinpdrInj500mg(): ?float
    {
        return $this->sduAmpicillinpdrInj500mg;
    }

    public function setSduAmpicillinpdrInj500mg(float $sduAmpicillinpdrInj500mg): static
    {
        $this->sduAmpicillinpdrInj500mg = $sduAmpicillinpdrInj500mg;

        return $this;
    }

    public function getSduGentamicininj40mg(): ?float
    {
        return $this->sduGentamicininj40mg;
    }

    public function setSduGentamicininj40mg(float $sduGentamicininj40mg): static
    {
        $this->sduGentamicininj40mg = $sduGentamicininj40mg;

        return $this;
    }

    public function getSduSodLactatInj500ml(): ?float
    {
        return $this->sduSodLactatInj500ml;
    }

    public function setSduSodLactatInj500ml(float $sduSodLactatInj500ml): static
    {
        $this->sduSodLactatInj500ml = $sduSodLactatInj500ml;

        return $this;
    }

    public function getSduGlucoseInj500ml(): ?float
    {
        return $this->sduGlucoseInj500ml;
    }

    public function setSduGlucoseInj500ml(float $sduGlucoseInj500ml): static
    {
        $this->sduGlucoseInj500ml = $sduGlucoseInj500ml;

        return $this;
    }

    public function getSduGlucoseHyperton50ml(): ?float
    {
        return $this->sduGlucoseHyperton50ml;
    }

    public function setSduGlucoseHyperton50ml(float $sduGlucoseHyperton50ml): static
    {
        $this->sduGlucoseHyperton50ml = $sduGlucoseHyperton50ml;

        return $this;
    }

    public function getSduFurosemideinj10mg(): ?float
    {
        return $this->sduFurosemideinj10mg;
    }

    public function setSduFurosemideinj10mg(float $sduFurosemideinj10mg): static
    {
        $this->sduFurosemideinj10mg = $sduFurosemideinj10mg;

        return $this;
    }

    public function getSduChlorhexidineConSol(): ?float
    {
        return $this->sduChlorhexidineConSol;
    }

    public function setSduChlorhexidineConSol(float $sduChlorhexidineConSol): static
    {
        $this->sduChlorhexidineConSol = $sduChlorhexidineConSol;

        return $this;
    }

    public function getSduMiconazoleNitrate(): ?float
    {
        return $this->sduMiconazoleNitrate;
    }

    public function setSduMiconazoleNitrate(float $sduMiconazoleNitrate): static
    {
        $this->sduMiconazoleNitrate = $sduMiconazoleNitrate;

        return $this;
    }

    public function getSduTetracyclineeyeointment(): ?float
    {
        return $this->sduTetracyclineeyeointment;
    }

    public function setSduTetracyclineeyeointment(float $sduTetracyclineeyeointment): static
    {
        $this->sduTetracyclineeyeointment = $sduTetracyclineeyeointment;

        return $this;
    }

    public function getSduTubeFeedingCH08(): ?float
    {
        return $this->sduTubeFeedingCH08;
    }

    public function setSduTubeFeedingCH08(float $sduTubeFeedingCH08): static
    {
        $this->sduTubeFeedingCH08 = $sduTubeFeedingCH08;

        return $this;
    }

    public function getSduTubeFeedingCH05(): ?float
    {
        return $this->sduTubeFeedingCH05;
    }

    public function setSduTubeFeedingCH05(float $sduTubeFeedingCH05): static
    {
        $this->sduTubeFeedingCH05 = $sduTubeFeedingCH05;

        return $this;
    }

    public function getSduSyringeDisp2ml(): ?float
    {
        return $this->sduSyringeDisp2ml;
    }

    public function setSduSyringeDisp2ml(float $sduSyringeDisp2ml): static
    {
        $this->sduSyringeDisp2ml = $sduSyringeDisp2ml;

        return $this;
    }

    public function getSduSyringeDisp10ml(): ?float
    {
        return $this->sduSyringeDisp10ml;
    }

    public function setSduSyringeDisp10ml(float $sduSyringeDisp10ml): static
    {
        $this->sduSyringeDisp10ml = $sduSyringeDisp10ml;

        return $this;
    }

    public function getSduSyringeDisp20ml(): ?float
    {
        return $this->sduSyringeDisp20ml;
    }

    public function setSduSyringeDisp20ml(float $sduSyringeDisp20ml): static
    {
        $this->sduSyringeDisp20ml = $sduSyringeDisp20ml;

        return $this;
    }

    public function getSduSyringeFeeding50ml(): ?float
    {
        return $this->sduSyringeFeeding50ml;
    }

    public function setSduSyringeFeeding50ml(float $sduSyringeFeeding50ml): static
    {
        $this->sduSyringeFeeding50ml = $sduSyringeFeeding50ml;

        return $this;
    }

    public function getSduCiprofloxacin250mg(): ?float
    {
        return $this->sduCiprofloxacin250mg;
    }

    public function setSduCiprofloxacin250mg(float $sduCiprofloxacin250mg): static
    {
        $this->sduCiprofloxacin250mg = $sduCiprofloxacin250mg;

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
            $dataValidationCreni->setDataCreni($this);
        }

        return $this;
    }

    public function removeDataValidationCreni(DataValidationCreni $dataValidationCreni): static
    {
        if ($this->dataValidationCrenis->removeElement($dataValidationCreni)) {
            // set the owning side to null (unless already changed)
            if ($dataValidationCreni->getDataCreni() === $this) {
                $dataValidationCreni->setDataCreni(null);
            }
        }

        return $this;
    }
}
