<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaiementRepository::class)
 */
class Paiement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $montantTtc;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $paidAt;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $paid;

    /**
     * @ORM\ManyToOne(targetEntity=Facture::class, inversedBy="paiements", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $facture;
    
    /**
     * @ORM\OneToMany(targetEntity=MoyenPaiement::class, mappedBy="paiement")
     */
    private $moyenPaiement;

    public function __construct()
    {
        $this->moyenPaiement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantTtc(): ?string
    {
        return $this->montantTtc;
    }

    public function setMontantTtc(string $montantTtc): self
    {
        $this->montantTtc = $montantTtc;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): self
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * @return Collection|MoyenPaiement[]
     */
    public function getMoyenPaiement(): Collection
    {
        return $this->moyenPaiement;
    }

    public function addMoyenPaiement(MoyenPaiement $moyenPaiement): self
    {
        if (!$this->moyenPaiement->contains($moyenPaiement)) {
            $this->moyenPaiement[] = $moyenPaiement;
            $moyenPaiement->setPaiement($this);
        }

        return $this;
    }

    public function removeMoyenPaiement(MoyenPaiement $moyenPaiement): self
    {
        if ($this->moyenPaiement->removeElement($moyenPaiement)) {
            // set the owning side to null (unless already changed)
            if ($moyenPaiement->getPaiement() === $this) {
                $moyenPaiement->setPaiement(null);
            }
        }

        return $this;
    }
}
