<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Table(name: '`region`')]
#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name:'nom')]
    private ?string $Nom = null;

    #[ORM\Column(length: 255, name:'chef_lieu')]
    private ?string $ChefLieu = null;

    #[ORM\Column(name: 'population')]
    private ?int $Population = null;

    #[ORM\Column(name: 'superficie')]
    private ?float $Superficie = null;

    #[ORM\Column(name: 'densite')]
    private ?float $Densite = null;

    #[ORM\ManyToOne(inversedBy: 'regions')]
    #[ORM\JoinColumn(nullable: false, name: 'province_id')]
    private ?Province $province = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: District::class)]
    private Collection $districts;

    #[ORM\OneToMany(mappedBy: 'Region', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'Region', targetEntity: RmaNut::class)]
    private Collection $rmaNuts;

    #[ORM\OneToMany(mappedBy: 'Region', targetEntity: Pvrd::class)]
    private Collection $pvrds;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->rmaNuts = new ArrayCollection();
        $this->pvrds = new ArrayCollection();
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

    public function getChefLieu(): ?string
    {
        return $this->ChefLieu;
    }

    public function setChefLieu(string $ChefLieu): static
    {
        $this->ChefLieu = $ChefLieu;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->Population;
    }

    public function setPopulation(int $Population): static
    {
        $this->Population = $Population;

        return $this;
    }

    public function getSuperficie(): ?float
    {
        return $this->Superficie;
    }

    public function setSuperficie(float $Superficie): static
    {
        $this->Superficie = $Superficie;

        return $this;
    }

    public function getDensite(): ?float
    {
        return $this->Densite;
    }

    public function setDensite(float $Densite): static
    {
        $this->Densite = $Densite;

        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): static
    {
        $this->province = $province;

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
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
            $district->setRegion($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): static
    {
        if ($this->districts->removeElement($district)) {
            // set the owning side to null (unless already changed)
            if ($district->getRegion() === $this) {
                $district->setRegion(null);
            }
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
            $user->setRegion($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRegion() === $this) {
                $user->setRegion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RmaNut>
     */
    public function getRmaNuts(): Collection
    {
        return $this->rmaNuts;
    }

    public function addRmaNut(RmaNut $rmaNut): static
    {
        if (!$this->rmaNuts->contains($rmaNut)) {
            $this->rmaNuts->add($rmaNut);
            $rmaNut->setRegion($this);
        }

        return $this;
    }

    public function removeRmaNut(RmaNut $rmaNut): static
    {
        if ($this->rmaNuts->removeElement($rmaNut)) {
            // set the owning side to null (unless already changed)
            if ($rmaNut->getRegion() === $this) {
                $rmaNut->setRegion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pvrd>
     */
    public function getPvrds(): Collection
    {
        return $this->pvrds;
    }

    public function addPvrd(Pvrd $pvrd): static
    {
        if (!$this->pvrds->contains($pvrd)) {
            $this->pvrds->add($pvrd);
            $pvrd->setRegion($this);
        }

        return $this;
    }

    public function removePvrd(Pvrd $pvrd): static
    {
        if ($this->pvrds->removeElement($pvrd)) {
            // set the owning side to null (unless already changed)
            if ($pvrd->getRegion() === $this) {
                $pvrd->setRegion(null);
            }
        }

        return $this;
    }
}
