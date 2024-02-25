<?php

namespace App\Entity;

use App\Repository\CentreHospitalierUniversitaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`centre_hospitalier_universitaire`')]
#[ORM\Entity(repositoryClass: CentreHospitalierUniversitaireRepository::class)]
class CentreHospitalierUniversitaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'nom')]
    private ?string $Nom = null;

    #[ORM\ManyToOne(inversedBy: 'centreHospitalierUniversitaires')]
    #[ORM\JoinColumn(nullable: true, name: 'district_id')]
    private ?District $District = null;

    #[ORM\OneToMany(mappedBy: 'CentreHospitalierUniversitaire', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->Nom;
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
            $user->setCentreHospitalierUniversitaire($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCentreHospitalierUniversitaire() === $this) {
                $user->setCentreHospitalierUniversitaire(null);
            }
        }

        return $this;
    }
}
