<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $pourcentageTva;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $prixTtc;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_debut_service;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_fin_service;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut_pret_service;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validation_admin;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPourcentageTva(): ?string
    {
        return $this->pourcentageTva;
    }

    public function setPourcentageTva(?string $pourcentageTva): self
    {
        $this->pourcentageTva = $pourcentageTva;

        return $this;
    }

    public function getPrixTtc(): ?string
    {
        return $this->prixTtc;
    }

    public function setPrixTtc(string $prixTtc): self
    {
        $this->prixTtc = $prixTtc;

        return $this;
    }

    public function getDateDebutService(): ?\DateTimeInterface
    {
        return $this->date_debut_service;
    }

    public function setDateDebutService(?\DateTimeInterface $date_debut_service): self
    {
        $this->date_debut_service = $date_debut_service;

        return $this;
    }

    public function getDateFinService(): ?\DateTimeInterface
    {
        return $this->date_fin_service;
    }

    public function setDateFinService(?\DateTimeInterface $date_fin_service): self
    {
        $this->date_fin_service = $date_fin_service;

        return $this;
    }

    public function getStatutPretService(): ?string
    {
        return $this->statut_pret_service;
    }

    public function setStatutPretService(?string $statut_pret_service): self
    {
        $this->statut_pret_service = $statut_pret_service;

        return $this;
    }

    public function getValidationAdmin(): ?bool
    {
        return $this->validation_admin;
    }

    public function setValidationAdmin(bool $validation_admin): self
    {
        $this->validation_admin = $validation_admin;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
