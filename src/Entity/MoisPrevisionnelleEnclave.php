<?php

namespace App\Entity;

use App\Repository\MoisPrevisionnelleEnclaveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`mois_previsionnelle_enclave`')]
#[ORM\Entity(repositoryClass: MoisPrevisionnelleEnclaveRepository::class)]
class MoisPrevisionnelleEnclave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true, name: 'mois')]
    private ?string $mois = null;

    #[ORM\ManyToOne(inversedBy: 'moisPrevisionnelleEnclaves')]
    #[ORM\JoinColumn(name: 'groupe_id')]
    private ?Groupe $groupe = null;

    #[ORM\OneToMany(mappedBy: 'moisPrevisionnelleEnclave', targetEntity: DataCrenasMoisProjectionAdmission::class)]
    private Collection $dataCrenasMoisProjectionAdmissions;

    public function __construct()
    {
        $this->dataCrenasMoisProjectionAdmissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(?string $mois): static
    {
        $this->mois = $mois;

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
            $dataCrenasMoisProjectionAdmission->setMoisPrevisionnelleEnclave($this);
        }

        return $this;
    }

    public function removeDataCrenasMoisProjectionAdmission(DataCrenasMoisProjectionAdmission $dataCrenasMoisProjectionAdmission): static
    {
        if ($this->dataCrenasMoisProjectionAdmissions->removeElement($dataCrenasMoisProjectionAdmission)) {
            // set the owning side to null (unless already changed)
            if ($dataCrenasMoisProjectionAdmission->getMoisPrevisionnelleEnclave() === $this) {
                $dataCrenasMoisProjectionAdmission->setMoisPrevisionnelleEnclave(null);
            }
        }

        return $this;
    }
}
