<?php

namespace App\Entity;

use App\Repository\PretRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PretRepository::class)
 */
class Pret
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
    private $date_debut_pret;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_fin_pret;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=StatusPret::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutPret(): ?\DateTimeInterface
    {
        return $this->date_debut_pret;
    }

    public function setDateDebutPret(\DateTimeInterface $date_debut_pret): self
    {
        $this->date_debut_pret = $date_debut_pret;

        return $this;
    }

    public function getDateFinPret(): ?\DateTimeInterface
    {
        return $this->date_fin_pret;
    }

    public function setDateFinPret(\DateTimeInterface $date_fin_pret): self
    {
        $this->date_fin_pret = $date_fin_pret;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getStatus(): ?StatusPret
    {
        return $this->status;
    }

    public function setStatus(?StatusPret $status): self
    {
        $this->status = $status;

        return $this;
    }
}
