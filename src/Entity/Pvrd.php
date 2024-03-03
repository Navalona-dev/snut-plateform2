<?php

namespace App\Entity;

use App\Repository\PvrdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`pvrd`')]
#[ORM\Entity(repositoryClass: PvrdRepository::class)]
class Pvrd
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'site')]
    private ?string $Site = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'date_reception')]
    private ?\DateTimeInterface $DateReception = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'date_pvrd')]
    private ?\DateTimeInterface $DatePvrd = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'date_televersement')]
    private ?\DateTimeInterface $DateTeleversement = null;

    #[ORM\Column(length: 255, name: 'numero_bon_livraison')]
    private ?string $NumeroBonLivraison = null;

    #[ORM\Column(length: 255, name: 'fournisseur')]
    private ?string $Fournisseur = null;

    #[ORM\OneToMany(mappedBy: 'Pvrd', targetEntity: PvrdProduit::class, orphanRemoval: true)]
    private Collection $pvrdProduits;

    #[ORM\ManyToOne(inversedBy: 'pvrds')]
    #[ORM\JoinColumn(nullable: false, name: 'responsable_district_id')]
    private ?User $ResponsableDistrict = null;

    #[ORM\Column(length: 255, name: 'original_file_name')]
    private ?string $OriginalFileName = null;

    #[ORM\Column(length: 255, name: 'new_file_name')]
    private ?string $NewFileName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'uploaded_date_time')]
    private ?\DateTimeInterface $UploadedDateTime = null;

    #[ORM\ManyToOne(inversedBy: 'pvrds')]
    #[ORM\JoinColumn(name: 'district_id')]
    private ?District $District = null;

    #[ORM\ManyToOne(inversedBy: 'pvrds')]
    #[ORM\JoinColumn(name: 'region_id')]
    private ?Region $Region = null;

    #[ORM\ManyToOne(inversedBy: 'pvrds')]
    #[ORM\JoinColumn(nullable: true, name: 'commandeTrimestrielle_id')]
    private ?CommandeTrimestrielle $commandeTrimestrielle = null;

    public function __construct()
    {
        $this->pvrdProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSite(): ?string
    {
        return $this->Site;
    }

    public function setSite(string $Site): static
    {
        $this->Site = $Site;

        return $this;
    }

    public function getDateReception(): ?\DateTimeInterface
    {
        return $this->DateReception;
    }

    public function setDateReception(\DateTimeInterface $DateReception): static
    {
        $this->DateReception = $DateReception;

        return $this;
    }

    public function getDatePvrd(): ?\DateTimeInterface
    {
        return $this->DatePvrd;
    }

    public function setDatePvrd(\DateTimeInterface $DatePvrd): static
    {
        $this->DatePvrd = $DatePvrd;

        return $this;
    }

    public function getDateTeleversement(): ?\DateTimeInterface
    {
        return $this->DateTeleversement;
    }

    public function setDateTeleversement(\DateTimeInterface $DateTeleversement): static
    {
        $this->DateTeleversement = $DateTeleversement;

        return $this;
    }

    public function getNumeroBonLivraison(): ?string
    {
        return $this->NumeroBonLivraison;
    }

    public function setNumeroBonLivraison(string $NumeroBonLivraison): static
    {
        $this->NumeroBonLivraison = $NumeroBonLivraison;

        return $this;
    }

    public function getFournisseur(): ?string
    {
        return $this->Fournisseur;
    }

    public function setFournisseur(string $Fournisseur): static
    {
        $this->Fournisseur = $Fournisseur;

        return $this;
    }

    /**
     * @return Collection<int, PvrdProduit>
     */
    public function getPvrdProduits(): Collection
    {
        return $this->pvrdProduits;
    }

    public function addPvrdProduit(PvrdProduit $pvrdProduit): static
    {
        if (!$this->pvrdProduits->contains($pvrdProduit)) {
            $this->pvrdProduits->add($pvrdProduit);
            $pvrdProduit->setPvrd($this);
        }

        return $this;
    }

    public function removePvrdProduit(PvrdProduit $pvrdProduit): static
    {
        if ($this->pvrdProduits->removeElement($pvrdProduit)) {
            // set the owning side to null (unless already changed)
            if ($pvrdProduit->getPvrd() === $this) {
                $pvrdProduit->setPvrd(null);
            }
        }

        return $this;
    }

    public function getResponsableDistrict(): ?User
    {
        return $this->ResponsableDistrict;
    }

    public function setResponsableDistrict(?User $ResponsableDistrict): static
    {
        $this->ResponsableDistrict = $ResponsableDistrict;

        return $this;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->OriginalFileName;
    }

    public function setOriginalFileName(string $OriginalFileName): static
    {
        $this->OriginalFileName = $OriginalFileName;

        return $this;
    }

    public function getNewFileName(): ?string
    {
        return $this->NewFileName;
    }

    public function setNewFileName(string $NewFileName): static
    {
        $this->NewFileName = $NewFileName;

        return $this;
    }

    public function getUploadedDateTime(): ?\DateTimeInterface
    {
        return $this->UploadedDateTime;
    }

    public function setUploadedDateTime(\DateTimeInterface $UploadedDateTime): static
    {
        $this->UploadedDateTime = $UploadedDateTime;

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

    public function getRegion(): ?Region
    {
        return $this->Region;
    }

    public function setRegion(?Region $Region): static
    {
        $this->Region = $Region;

        return $this;
    }

    public function getCommandeTrimestrielle(): ?CommandeTrimestrielle
    {
        return $this->commandeTrimestrielle;
    }

    public function setCommandeTrimestrielle(?CommandeTrimestrielle $commandeTrimestrielle): static
    {
        $this->commandeTrimestrielle = $commandeTrimestrielle;

        return $this;
    }
}
