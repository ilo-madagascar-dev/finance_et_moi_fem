<?php

namespace App\Entity;

use App\Repository\StatusPretRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusPretRepository::class)
 */
class StatusPret
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
    private $libelle_status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description_status;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="status")
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleStatus(): ?string
    {
        return $this->libelle_status;
    }

    public function setLibelleStatus(string $libelle_status): self
    {
        $this->libelle_status = $libelle_status;

        return $this;
    }

    public function getDescriptionStatus(): ?string
    {
        return $this->description_status;
    }

    public function setDescriptionStatus(?string $description_status): self
    {
        $this->description_status = $description_status;

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setStatus($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getStatus() === $this) {
                $pret->setStatus(null);
            }
        }

        return $this;
    }
}
