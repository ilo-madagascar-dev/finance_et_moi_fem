<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
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
}
