<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AbonnementRepository::class)
 */
class Abonnement
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
    private $date_debut_abonnement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_fin_abonnement;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @ORM\Column(type="text")
     */
    private $stripe_subscription_id;

    /**
     * @ORM\Column(type="text")
     */
    private $stripe_cus_id;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="abonnement", orphanRemoval=true)
     */
    private $factures;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut_paiement;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="abonnement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutAbonnement(): ?\DateTimeInterface
    {
        return $this->date_debut_abonnement;
    }

    public function setDateDebutAbonnement(\DateTimeInterface $date_debut_abonnement): self
    {
        $this->date_debut_abonnement = $date_debut_abonnement;

        return $this;
    }

    public function getDateFinAbonnement(): ?\DateTimeInterface
    {
        return $this->date_fin_abonnement;
    }

    public function setDateFinAbonnement(?\DateTimeInterface $date_fin_abonnement): self
    {
        $this->date_fin_abonnement = $date_fin_abonnement;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getStripeSubscriptionId(): ?string
    {
        return $this->stripe_subscription_id;
    }

    public function setStripeSubscriptionId(string $stripe_subscription_id): self
    {
        $this->stripe_subscription_id = $stripe_subscription_id;

        return $this;
    }

    public function getStripeCusId(): ?string
    {
        return $this->stripe_cus_id;
    }

    public function setStripeCusId(string $stripe_cus_id): self
    {
        $this->stripe_cus_id = $stripe_cus_id;

        return $this;
    }

    /**
     * @return Collection|Facture[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setAbonnement($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getAbonnement() === $this) {
                $facture->setAbonnement(null);
            }
        }

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getStatutPaiement(): ?bool
    {
        return $this->statut_paiement;
    }

    public function setStatutPaiement(bool $statut_paiement): self
    {
        $this->statut_paiement = $statut_paiement;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
