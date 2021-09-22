<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_emission_facture;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $pourcentage_tva;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private $montant_ttc_facture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $facture_acquitee;

    /**
     * @ORM\ManyToOne(targetEntity=Abonnement::class, inversedBy="factures", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $abonnement;

    /**
     * @ORM\OneToMany(targetEntity=Paiement::class, mappedBy="facture")
     */
    private $paiements;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    private $montantHT;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmissionFacture(): ?\DateTimeInterface
    {
        return $this->date_emission_facture;
    }

    public function setDateEmissionFacture(\DateTimeInterface $date_emission_facture): self
    {
        $this->date_emission_facture = $date_emission_facture;

        return $this;
    }

    public function getPourcentageTva(): ?string
    {
        return $this->pourcentage_tva;
    }

    public function setPourcentageTva(string $pourcentage_tva): self
    {
        $this->pourcentage_tva = $pourcentage_tva;

        return $this;
    }

    public function getMontantTtcFacture(): ?string
    {
        return $this->montant_ttc_facture;
    }

    public function setMontantTtcFacture(string $montant_ttc_facture): self
    {
        $this->montant_ttc_facture = $montant_ttc_facture;

        return $this;
    }

    public function getFactureAcquitee(): ?bool
    {
        return $this->facture_acquitee;
    }

    public function setFactureAcquitee(bool $facture_acquitee): self
    {
        $this->facture_acquitee = $facture_acquitee;

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->abonnement;
    }

    public function setAbonnement(?Abonnement $abonnement): self
    {
        $this->abonnement = $abonnement;

        return $this;
    }

    /**
     * @return Collection|Paiement[]
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements[] = $paiement;
            $paiement->setFacture($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getFacture() === $this) {
                $paiement->setFacture(null);
            }
        }

        return $this;
    }

    public function getMontantHT(): ?string
    {
        return $this->montantHT;
    }

    public function setMontantHT(?string $montantHT): self
    {
        $this->montantHT = $montantHT;

        return $this;
    }
}
