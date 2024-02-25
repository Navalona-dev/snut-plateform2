<?php

namespace App\Entity;

use App\Repository\DistrictRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Table(name: '`district`')]
#[ORM\Entity(repositoryClass: DistrictRepository::class)]
class District
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'nom')]
    private ?string $Nom = null;

    #[ORM\Column(name: 'superficie')]
    private ?float $Superficie = null;

    #[ORM\Column(name: 'population')]
    private ?int $Population = null;

    #[ORM\Column(nullable: true, name: 'densite')]
    private ?float $Densite = null;

    #[ORM\ManyToOne(inversedBy: 'districts')]
    #[ORM\JoinColumn(nullable: true, name: 'region_id')]
    private ?Region $region = null;

    #[ORM\OneToMany(mappedBy: 'District', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column(name: 'is_eligible_for_creni')]
    private ?bool $isEligibleForCreni = null;

    #[ORM\OneToMany(mappedBy: 'District', targetEntity: CentreHospitalierUniversitaire::class)]
    private Collection $centreHospitalierUniversitaires;

    #[ORM\Column(name: 'is_eligible_for_crenas')]
    private ?bool $isEligibleForCrenas = null;

    #[ORM\OneToMany(mappedBy: 'District', targetEntity: RmaNut::class)]
    private Collection $rmaNuts;

    #[ORM\OneToMany(mappedBy: 'District', targetEntity: Pvrd::class)]
    private Collection $pvrds;

    #[ORM\OneToMany(mappedBy: 'district', targetEntity: StockData::class)]
    private Collection $stockData;

    #[ORM\ManyToOne(inversedBy: 'districts')]
    #[ORM\JoinColumn(nullable: true, name: 'province_id')]
    private ?Province $province = null;

    #[ORM\Column(length: 255, nullable: true, name: 'type')]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: Groupe::class, mappedBy: 'districts')]
    private Collection $groupes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->centreHospitalierUniversitaires = new ArrayCollection();
        $this->rmaNuts = new ArrayCollection();
        $this->pvrds = new ArrayCollection();
        $this->stockData = new ArrayCollection();
        $this->groupes = new ArrayCollection();
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

    public function getSuperficie(): ?float
    {
        return $this->Superficie;
    }

    public function setSuperficie(float $Superficie): static
    {
        $this->Superficie = $Superficie;

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

    public function getDensite(): ?float
    {
        return $this->Densite;
    }

    public function setDensite(?float $Densite): static
    {
        $this->Densite = $Densite;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function __toString()
    {
        return $this->Nom;
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
            $user->setDistrict($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getDistrict() === $this) {
                $user->setDistrict(null);
            }
        }

        return $this;
    }

    public function isIsEligibleForCreni(): ?bool
    {
        return $this->isEligibleForCreni;
    }

    public function setIsEligibleForCreni(bool $isEligibleForCreni): static
    {
        $this->isEligibleForCreni = $isEligibleForCreni;

        return $this;
    }

    /**
     * @return Collection<int, CentreHospitalierUniversitaire>
     */
    public function getCentreHospitalierUniversitaires(): Collection
    {
        return $this->centreHospitalierUniversitaires;
    }

    public function addCentreHospitalierUniversitaire(CentreHospitalierUniversitaire $centreHospitalierUniversitaire): static
    {
        if (!$this->centreHospitalierUniversitaires->contains($centreHospitalierUniversitaire)) {
            $this->centreHospitalierUniversitaires->add($centreHospitalierUniversitaire);
            $centreHospitalierUniversitaire->setDistrict($this);
        }

        return $this;
    }

    public function removeCentreHospitalierUniversitaire(CentreHospitalierUniversitaire $centreHospitalierUniversitaire): static
    {
        if ($this->centreHospitalierUniversitaires->removeElement($centreHospitalierUniversitaire)) {
            // set the owning side to null (unless already changed)
            if ($centreHospitalierUniversitaire->getDistrict() === $this) {
                $centreHospitalierUniversitaire->setDistrict(null);
            }
        }

        return $this;
    }

    public function isIsEligibleForCrenas(): ?bool
    {
        return $this->isEligibleForCrenas;
    }

    public function setIsEligibleForCrenas(bool $isEligibleForCrenas): static
    {
        $this->isEligibleForCrenas = $isEligibleForCrenas;

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
            $rmaNut->setDistrict($this);
        }

        return $this;
    }

    public function removeRmaNut(RmaNut $rmaNut): static
    {
        if ($this->rmaNuts->removeElement($rmaNut)) {
            // set the owning side to null (unless already changed)
            if ($rmaNut->getDistrict() === $this) {
                $rmaNut->setDistrict(null);
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
            $pvrd->setDistrict($this);
        }

        return $this;
    }

    public function removePvrd(Pvrd $pvrd): static
    {
        if ($this->pvrds->removeElement($pvrd)) {
            // set the owning side to null (unless already changed)
            if ($pvrd->getDistrict() === $this) {
                $pvrd->setDistrict(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StockData>
     */
    public function getStockData(): Collection
    {
        return $this->stockData;
    }

    public function addStockData(StockData $stockData): static
    {
        if (!$this->stockData->contains($stockData)) {
            $this->stockData->add($stockData);
            $stockData->setDistrict($this);
        }

        return $this;
    }

    public function removeStockData(StockData $stockData): static
    {
        if ($this->stockData->removeElement($stockData)) {
            // set the owning side to null (unless already changed)
            if ($stockData->getDistrict() === $this) {
                $stockData->setDistrict(null);
            }
        }

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

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
            $groupe->addDistrict($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeDistrict($this);
        }

        return $this;
    }
}
