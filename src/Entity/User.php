<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Cet e-mail est déjà utilisé. Veuillez en choisir un autre.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true, name: 'email')]
    private ?string $email = null;

    #[ORM\Column(name: 'roles')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(name: 'password')]
    private ?string $password = null;

    #[ORM\Column(length: 255, name: 'nom')]
    private ?string $Nom = null;

    #[ORM\Column(length: 255, name: 'prenoms')]
    private ?string $Prenoms = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true, name: 'province_id')]
    private ?Province $Province = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true, name: 'region_id')]
    private ?Region $Region = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true, name: 'district_id')]
    private ?District $District = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: DataCrenas::class)]
    private Collection $dataCrenas;

    #[ORM\OneToMany(mappedBy: 'uploadedBy', targetEntity: RmaNut::class)]
    private Collection $rmaNuts;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: DataCreni::class)]
    private Collection $dataCrenis;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'centre_hospitalier_universitaire_id')]
    private ?CentreHospitalierUniversitaire $CentreHospitalierUniversitaire = null;

    #[ORM\Column(length: 10)]
    private ?string $Telephone = null;

    #[ORM\OneToMany(mappedBy: 'ResponsableDistrict', targetEntity: Pvrd::class, orphanRemoval: true)]
    private Collection $pvrds; 

    public function __construct()
    {
        $this->dataCrenas = new ArrayCollection();
        $this->rmaNuts = new ArrayCollection();
        $this->dataCrenis = new ArrayCollection();
        $this->pvrds = new ArrayCollection();
    } 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role)
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenoms(): ?string
    {
        return $this->Prenoms;
    }

    public function setPrenoms(string $Prenoms): static
    {
        $this->Prenoms = $Prenoms;

        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->Province;
    }

    public function setProvince(?Province $Province): static
    {
        $this->Province = $Province;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->Region;
    }

    public function setRegion(?Region $Region): static
    {
        $this->Region = $Region;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->District;
    }

    public function setDistrict(?District $District): static
    {
        $this->District = $District;

        return $this;
    }

    /**
     * @return Collection<int, DataCrenas>
     */
    public function getDataCrenas(): Collection
    {
        return $this->dataCrenas;
    }

    public function addDataCrena(DataCrenas $dataCrena): static
    {
        if (!$this->dataCrenas->contains($dataCrena)) {
            $this->dataCrenas->add($dataCrena);
            $dataCrena->setUser($this);
        }

        return $this;
    }

    public function removeDataCrena(DataCrenas $dataCrena): static
    {
        if ($this->dataCrenas->removeElement($dataCrena)) {
            // set the owning side to null (unless already changed)
            if ($dataCrena->getUser() === $this) {
                $dataCrena->setUser(null);
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
            $rmaNut->setUploadedBy($this);
        }

        return $this;
    }

    public function removeRmaNut(RmaNut $rmaNut): static
    {
        if ($this->rmaNuts->removeElement($rmaNut)) {
            // set the owning side to null (unless already changed)
            if ($rmaNut->getUploadedBy() === $this) {
                $rmaNut->setUploadedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DataCreni>
     */
    public function getDataCrenis(): Collection
    {
        return $this->dataCrenis;
    }

    public function addDataCreni(DataCreni $dataCreni): static
    {
        if (!$this->dataCrenis->contains($dataCreni)) {
            $this->dataCrenis->add($dataCreni);
            $dataCreni->setUser($this);
        }

        return $this;
    }

    public function removeDataCreni(DataCreni $dataCreni): static
    {
        if ($this->dataCrenis->removeElement($dataCreni)) {
            // set the owning side to null (unless already changed)
            if ($dataCreni->getUser() === $this) {
                $dataCreni->setUser(null);
            }
        }

        return $this;
    }

    public function getCentreHospitalierUniversitaire(): ?CentreHospitalierUniversitaire
    {
        return $this->CentreHospitalierUniversitaire;
    }

    public function setCentreHospitalierUniversitaire(?CentreHospitalierUniversitaire $CentreHospitalierUniversitaire): static
    {
        $this->CentreHospitalierUniversitaire = $CentreHospitalierUniversitaire;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->Telephone;
    }

    public function setTelephone(string $Telephone): static
    {
        $this->Telephone = $Telephone;

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
            $pvrd->setResponsableDistrict($this);
        }

        return $this;
    }

    public function removePvrd(Pvrd $pvrd): static
    {
        if ($this->pvrds->removeElement($pvrd)) {
            // set the owning side to null (unless already changed)
            if ($pvrd->getResponsableDistrict() === $this) {
                $pvrd->setResponsableDistrict(null);
            }
        }

        return $this;
    } 
 
}
