<?php

namespace App\Entity;

use App\Repository\AnneePrevisionnelleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`annee_previsionnelle`')]
#[ORM\Entity(repositoryClass: AnneePrevisionnelleRepository::class)]
class AnneePrevisionnelle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'annee')]
    private ?int $Annee = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_atpe01')]
    private ?float $ValeurCalculTheoriqueATPE01 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_amox01')]
    private ?float $ValeurCalculTheoriqueAMOX01 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_fiche_patient01')]
    private ?float $ValeurCalculTheoriqueFichePatient01 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_registre01')]
    private ?float $ValeurCalculTheoriqueRegistre01 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_carnet_rapport01')]
    private ?float $ValeurCalculTheoriqueCarnetRapport01 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_atpe02')]
    private ?float $ValeurCalculTheoriqueATPE02 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_amox02')]
    private ?float $ValeurCalculTheoriqueAMOX02 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_fiche_patient02')]
    private ?float $ValeurCalculTheoriqueFichePatient02 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_registre02')]
    private ?float $ValeurCalculTheoriqueRegistre02 = null;

    #[ORM\Column(name: 'valeur_calcul_theorique_carnet_rapport02')]
    private ?float $ValeurCalculTheoriqueCarnetRapport02 = null;

    #[ORM\OneToMany(mappedBy: 'Annee', targetEntity: Groupe::class)]
    private Collection $groupes;

    #[ORM\OneToMany(mappedBy: 'AnneePrevisionnelle', targetEntity: CommandeTrimestrielle::class)]
    private Collection $commandeTrimestrielles;

    #[ORM\OneToMany(mappedBy: 'AnneePrevisionnelle', targetEntity: CommandeSemestrielle::class)]
    private Collection $commandeSemestrielles; 

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->commandeTrimestrielles = new ArrayCollection();
        $this->commandeSemestrielles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->Annee;
    }

    public function setAnnee(int $Annee): static
    {
        $this->Annee = $Annee;

        return $this;
    }

    public function getValeurCalculTheoriqueATPE01(): ?float
    {
        return $this->ValeurCalculTheoriqueATPE01;
    }

    public function setValeurCalculTheoriqueATPE01(float $ValeurCalculTheoriqueATPE01): static
    {
        $this->ValeurCalculTheoriqueATPE01 = $ValeurCalculTheoriqueATPE01;

        return $this;
    }

    public function getValeurCalculTheoriqueAMOX01(): ?float
    {
        return $this->ValeurCalculTheoriqueAMOX01;
    }

    public function setValeurCalculTheoriqueAMOX01(float $ValeurCalculTheoriqueAMOX01): static
    {
        $this->ValeurCalculTheoriqueAMOX01 = $ValeurCalculTheoriqueAMOX01;

        return $this;
    }

    public function getValeurCalculTheoriqueFichePatient01(): ?float
    {
        return $this->ValeurCalculTheoriqueFichePatient01;
    }

    public function setValeurCalculTheoriqueFichePatient01(float $ValeurCalculTheoriqueFichePatient01): static
    {
        $this->ValeurCalculTheoriqueFichePatient01 = $ValeurCalculTheoriqueFichePatient01;

        return $this;
    }

    public function getValeurCalculTheoriqueATPE02(): ?float
    {
        return $this->ValeurCalculTheoriqueATPE02;
    }

    public function setValeurCalculTheoriqueATPE02(float $ValeurCalculTheoriqueATPE02): static
    {
        $this->ValeurCalculTheoriqueATPE02 = $ValeurCalculTheoriqueATPE02;

        return $this;
    }

    public function getValeurCalculTheoriqueAMOX02(): ?float
    {
        return $this->ValeurCalculTheoriqueAMOX02;
    }

    public function setValeurCalculTheoriqueAMOX02(float $ValeurCalculTheoriqueAMOX02): static
    {
        $this->ValeurCalculTheoriqueAMOX02 = $ValeurCalculTheoriqueAMOX02;

        return $this;
    }

    public function getValeurCalculTheoriqueFichePatient02(): ?float
    {
        return $this->ValeurCalculTheoriqueFichePatient02;
    }

    public function setValeurCalculTheoriqueFichePatient02(float $ValeurCalculTheoriqueFichePatient02): static
    {
        $this->ValeurCalculTheoriqueFichePatient02 = $ValeurCalculTheoriqueFichePatient02;

        return $this;
    }

    public function __toString()
    {
        return $this->Annee; // Remplacez par la propriété de votre classe que vous souhaitez afficher
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->setAnnee($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getAnnee() === $this) {
                $groupe->setAnnee(null);
            }
        }

        return $this;
    }

    public function getValeurCalculTheoriqueRegistre01(): ?float
    {
        return $this->ValeurCalculTheoriqueRegistre01;
    }

    public function setValeurCalculTheoriqueRegistre01(float $ValeurCalculTheoriqueRegistre01): static
    {
        $this->ValeurCalculTheoriqueRegistre01 = $ValeurCalculTheoriqueRegistre01;

        return $this;
    }

    public function getValeurCalculTheoriqueCarnetRapport01(): ?float
    {
        return $this->ValeurCalculTheoriqueCarnetRapport01;
    }

    public function setValeurCalculTheoriqueCarnetRapport01(float $ValeurCalculTheoriqueCarnetRapport01): static
    {
        $this->ValeurCalculTheoriqueCarnetRapport01 = $ValeurCalculTheoriqueCarnetRapport01;

        return $this;
    }

    public function getValeurCalculTheoriqueRegistre02(): ?float
    {
        return $this->ValeurCalculTheoriqueRegistre02;
    }

    public function setValeurCalculTheoriqueRegistre02(float $ValeurCalculTheoriqueRegistre02): static
    {
        $this->ValeurCalculTheoriqueRegistre02 = $ValeurCalculTheoriqueRegistre02;

        return $this;
    }

    public function getValeurCalculTheoriqueCarnetRapport02(): ?float
    {
        return $this->ValeurCalculTheoriqueCarnetRapport02;
    }

    public function setValeurCalculTheoriqueCarnetRapport02(float $ValeurCalculTheoriqueCarnetRapport02): static
    {
        $this->ValeurCalculTheoriqueCarnetRapport02 = $ValeurCalculTheoriqueCarnetRapport02;

        return $this;
    }

    /**
     * @return Collection<int, CommandeTrimestrielle>
     */
    public function getCommandeTrimestrielles(): Collection
    {
        return $this->commandeTrimestrielles;
    }

    public function addCommandeTrimestrielle(CommandeTrimestrielle $commandeTrimestrielle): static
    {
        if (!$this->commandeTrimestrielles->contains($commandeTrimestrielle)) {
            $this->commandeTrimestrielles->add($commandeTrimestrielle);
            $commandeTrimestrielle->setAnneePrevisionnelle($this);
        }

        return $this;
    }

    public function removeCommandeTrimestrielle(CommandeTrimestrielle $commandeTrimestrielle): static
    {
        if ($this->commandeTrimestrielles->removeElement($commandeTrimestrielle)) {
            // set the owning side to null (unless already changed)
            if ($commandeTrimestrielle->getAnneePrevisionnelle() === $this) {
                $commandeTrimestrielle->setAnneePrevisionnelle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeSemestrielle>
     */
    public function getCommandeSemestrielles(): Collection
    {
        return $this->commandeSemestrielles;
    }

    public function addCommandeSemestrielle(CommandeSemestrielle $commandeSemestrielle): static
    {
        if (!$this->commandeSemestrielles->contains($commandeSemestrielle)) {
            $this->commandeSemestrielles->add($commandeSemestrielle);
            $commandeSemestrielle->setAnneePrevisionnelle($this);
        }

        return $this;
    }

    public function removeCommandeSemestrielle(CommandeSemestrielle $commandeSemestrielle): static
    {
        if ($this->commandeSemestrielles->removeElement($commandeSemestrielle)) {
            // set the owning side to null (unless already changed)
            if ($commandeSemestrielle->getAnneePrevisionnelle() === $this) {
                $commandeSemestrielle->setAnneePrevisionnelle(null);
            }
        }

        return $this;
    }
}
