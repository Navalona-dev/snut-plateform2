<?php

namespace App\Entity;

use App\Repository\ProvinceRepository;
use App\Validator\Constraints as CustomAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Table(name: '`province`')]
#[ORM\Entity(repositoryClass: ProvinceRepository::class)]
class Province
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name:'nom_fr')]
    private ?string $NomFR = null;

    #[ORM\Column(length: 255, name:'nom_mg')]
    private ?string $NomMG = null;

    #[ORM\Column(length: 255, name:'chef_lieu')]
    private ?string $ChefLieu = null;

    #[ORM\Column(length: 50, name:'code_iso')]
    private ?string $CodeISO = null;

    #[ORM\Column(length: 50, name:'code_fips')]
    private ?string $CodeFIPS = null;

    #[ORM\Column(nullable: true, name:'superficie')]
    private ?float $Superficie = null;

    #[ORM\Column(nullable: true, name:'population')]
    private ?int $Population = null;

    #[ORM\OneToMany(mappedBy: 'province', targetEntity: Region::class)]
    private Collection $regions;

    #[ORM\ManyToMany(targetEntity: Groupe::class, mappedBy: 'Provinces')]
    private Collection $groupes;

    #[ORM\OneToMany(mappedBy: 'Province', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'province', targetEntity: District::class)]
    private Collection $districts;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->districts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFR(): ?string
    {
        return $this->NomFR;
    }

    public function setNomFR(string $NomFR): static
    {
        $this->NomFR = $NomFR;

        return $this;
    }

    public function getNomMG(): ?string
    {
        return $this->NomMG;
    }

    public function setNomMG(string $NomMG): static
    {
        $this->NomMG = $NomMG;

        return $this;
    }

    public function getChefLieu(): ?string
    {
        return $this->ChefLieu;
    }

    public function setChefLieu(string $ChefLieu): static
    {
        $this->ChefLieu = $ChefLieu;

        return $this;
    }

    public function getCodeISO(): ?string
    {
        return $this->CodeISO;
    }

    public function setCodeISO(string $CodeISO): static
    {
        $this->CodeISO = $CodeISO;

        return $this;
    }

    public function getCodeFIPS(): ?string
    {
        return $this->CodeFIPS;
    }

    public function setCodeFIPS(string $CodeFIPS): static
    {
        $this->CodeFIPS = $CodeFIPS;

        return $this;
    }

    public function getSuperficie(): ?float
    {
        return $this->Superficie;
    }

    public function setSuperficie(?float $Superficie): static
    {
        $this->Superficie = $Superficie;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->Population;
    }

    public function setPopulation(?int $Population): static
    {
        $this->Population = $Population;

        return $this;
    }
    
    public function __toString()
    {
        return $this->getNomFR() . " | ". $this->getNomMG(); // Renvoie le nom de la province
    }

    /**
     * @return Collection<int, Region>
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): static
    {
        if (!$this->regions->contains($region)) {
            $this->regions->add($region);
            $region->setProvince($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): static
    {
        if ($this->regions->removeElement($region)) {
            // set the owning side to null (unless already changed)
            if ($region->getProvince() === $this) {
                $region->setProvince(null);
            }
        }

        return $this;
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
            $groupe->addProvince($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeProvince($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setProvince($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProvince() === $this) {
                $user->setProvince(null);
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
            $district->setProvince($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): static
    {
        if ($this->districts->removeElement($district)) {
            // set the owning side to null (unless already changed)
            if ($district->getProvince() === $this) {
                $district->setProvince(null);
            }
        }

        return $this;
    } 
}
