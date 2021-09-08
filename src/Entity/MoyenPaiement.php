<?php

namespace App\Entity;

use App\Repository\MoyenPaiementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoyenPaiementRepository::class)
 */
class MoyenPaiement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $metadata;

    /**
     * @ORM\ManyToOne(targetEntity=Paiement::class, inversedBy="moyenPaiement")
     */
    private $paiement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(string $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): self
    {
        $this->paiement = $paiement;

        return $this;
    }
}
