<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
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
    protected $nomEntreprise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telMobile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telFixe;

    /**
     * @ORM\Column(type="text")
     */
    private $uniqid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vd;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stripeToken;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $siren;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="client", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="client", orphanRemoval=true)
     */
    private $prets;

    /**
     * @ORM\OneToOne(targetEntity=Abonnement::class, mappedBy="client", cascade={"persist", "remove"})
     */
    private $abonnement;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): self
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    public function getTelMobile(): ?string
    {
        return $this->telMobile;
    }

    public function setTelMobile(string $telMobile): self
    {
        $this->telMobile = $telMobile;

        return $this;
    }

    public function getTelFixe(): ?string
    {
        return $this->telFixe;
    }

    public function setTelFixe(string $telFixe): self
    {
        $this->telFixe = $telFixe;

        return $this;
    }

    public function getUniqid(): ?string
    {
        return $this->uniqid;
    }

    public function setUniqid(string $uniqid): self
    {
        $this->uniqid = $uniqid;

        return $this;
    }

    public function getVd(): ?string
    {
        return $this->vd;
    }

    public function setVd(string $vd): self
    {
        $this->vd = $vd;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripeToken;
    }

    public function setStripeToken(string $stripeToken): self
    {
        $this->stripeToken = $stripeToken;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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
            $pret->setClient($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getClient() === $this) {
                $pret->setClient(null);
            }
        }

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->abonnement;
    }

    public function setAbonnement(Abonnement $abonnement): self
    {
        // set the owning side of the relation if necessary
        if ($abonnement->getClient() !== $this) {
            $abonnement->setClient($this);
        }

        $this->abonnement = $abonnement;

        return $this;
    }
}
