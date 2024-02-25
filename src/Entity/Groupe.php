<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`groupe`')]
#[ORM\Entity(repositoryClass: GroupeRepository::class)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, name: 'nom')]
    private ?string $Nom = null;

    #[ORM\ManyToMany(targetEntity: Province::class, inversedBy: 'groupes')]
    private Collection $Provinces;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    #[ORM\JoinColumn(nullable: true, name: 'annee_id')]
    private ?AnneePrevisionnelle $Annee = null;

    #[ORM\OneToMany(mappedBy: 'Groupe', targetEntity: MoisProjectionsAdmissions::class)]
    private Collection $moisProjectionsAdmissions;

    #[ORM\ManyToMany(targetEntity: District::class, inversedBy: 'groupes')]
    private Collection $districts;

    #[ORM\Column(length: 255, nullable: true, name: 'zone')]
    private ?string $zone = null;

    public function __construct()
    {
        $this->Provinces = new ArrayCollection();
        $this->moisProjectionsAdmissions = new ArrayCollection();
        $this->districts = new ArrayCollection();
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

    public function __toString()
    {
        return $this->Annee . " - ". $this->Nom; // Remplacez par la propriété de votre classe que vous souhaitez afficher
    }

    /**
     * @return Collection<int, Province>
     */
    public function getProvinces(): Collection
    {
        return $this->Provinces;
    }

    public function addProvince(Province $province): static
    {
        if (!$this->Provinces->contains($province)) {
            $this->Provinces->add($province);
        }

        return $this;
    }

    public function removeProvince(Province $province): static
    {
        $this->Provinces->removeElement($province);

        return $this;
    }

    public function getAnnee(): ?AnneePrevisionnelle
    {
        return $this->Annee;
    }

    public function setAnnee(?AnneePrevisionnelle $Annee): static
    {
        $this->Annee = $Annee;

        return $this;
    }

    /**
     * @return Collection<int, MoisProjectionsAdmissions>
     */
    public function getMoisProjectionsAdmissions(): Collection
    {
        return $this->moisProjectionsAdmissions;
    }

    public function addMoisProjectionsAdmission(MoisProjectionsAdmissions $moisProjectionsAdmission): static
    {
        if (!$this->moisProjectionsAdmissions->contains($moisProjectionsAdmission)) {
            $this->moisProjectionsAdmissions->add($moisProjectionsAdmission);
            $moisProjectionsAdmission->setGroupe($this);
        }

        return $this;
    }

    public function removeMoisProjectionsAdmission(MoisProjectionsAdmissions $moisProjectionsAdmission): static
    {
        if ($this->moisProjectionsAdmissions->removeElement($moisProjectionsAdmission)) {
            // set the owning side to null (unless already changed)
            if ($moisProjectionsAdmission->getGroupe() === $this) {
                $moisProjectionsAdmission->setGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, District>
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    public function addDistrict(District $district): static
    {
        if (!$this->districts->contains($district)) {
            $this->districts->add($district);
        }

        return $this;
    }

    public function removeDistrict(District $district): static
    {
        $this->districts->removeElement($district);

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(?string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }
}
