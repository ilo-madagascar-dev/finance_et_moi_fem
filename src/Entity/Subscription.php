<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
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
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="subscription", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

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

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

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
