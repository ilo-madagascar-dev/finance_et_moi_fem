<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @Vich\Uploadable
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

    /**
     * @ORM\OneToMany(targetEntity=SousCompte::class, mappedBy="client", orphanRemoval=true)
     */
    private $sousComptes;

    /**
     * 
     * @Vich\UploadableField(mapping="identity_proof", fileNameProperty="identityProof")
     * 
     */
    private $identityProofFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $identityProof;

    /**
     * 
     * @Vich\UploadableField(mapping="RCS", fileNameProperty="extraitRCSname")
     * 
     */
    private $extraitRCSFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $extraitRCSname;

    /**
     * 
     * @Vich\UploadableField(mapping="rib", fileNameProperty="rib")
     * 
     */
    private $ribFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $rib;
    
    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rcsValidated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $identityValidated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ribValidated;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $actif;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statutJuridique;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
        $this->sousComptes = new ArrayCollection();
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

    /**
     * @return Collection|SousCompte[]
     */
    public function getSousComptes(): Collection
    {
        return $this->sousComptes;
    }

    public function addSousCompte(SousCompte $sousCompte): self
    {
        if (!$this->sousComptes->contains($sousCompte)) {
            $this->sousComptes[] = $sousCompte;
            $sousCompte->setClient($this);
        }

        return $this;
    }

    public function removeSousCompte(SousCompte $sousCompte): self
    {
        if ($this->sousComptes->removeElement($sousCompte)) {
            // set the owning side to null (unless already changed)
            if ($sousCompte->getClient() === $this) {
                $sousCompte->setClient(null);
            }
        }

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $extraitRCSFile
     */
    public function setExtraitRCSFile($extraitRCSFile = null): Client
    {
        $this->extraitRCSFile = $extraitRCSFile;

        if (null !== $extraitRCSFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getExtraitRCSFile()
    {
        return $this->extraitRCSFile;
    }

    public function setExtraitRCSname(?string $extraitRCSname): Client
    {
        $this->extraitRCSname = $extraitRCSname;
        return $this;
    }

    public function getExtraitRCSname(): ?string
    {
        return $this->extraitRCSname;
    }

    /**
     * 
     * Rib File
     * 
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null ribFile
     */
    public function setRibFile($ribFile = null): Client
    {
        $this->ribFile = $ribFile;

        if (null !== $ribFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getRibFile()
    {
        return $this->ribFile;
    }

    public function setRib(?string $rib): Client
    {
        $this->rib = $rib;
        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }
    
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $identityProofFile
     */
    public function setIdentityProofFile($identityProofFile = null): Client
    {
        $this->identityProofFile = $identityProofFile;

        if (null !== $identityProofFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getIdentityProofFile()
    {
        return $this->identityProofFile;
    }

    public function setIdentityProof(?string $identityProof): Client
    {
        $this->identityProof = $identityProof;
        return $this;
    }

    public function getIdentityProof(): ?string
    {
        return $this->identityProof;
    }

    public function getRcsValidated(): ?bool
    {
        return $this->rcsValidated;
    }

    public function setRcsValidated(?bool $rcsValidated): self
    {
        $this->rcsValidated = $rcsValidated;

        return $this;
    }

    public function getIdentityValidated(): ?bool
    {
        return $this->identityValidated;
    }

    public function setIdentityValidated(?bool $identityValidated): self
    {
        $this->identityValidated = $identityValidated;

        return $this;
    }

    public function getRibValidated(): ?bool
    {
        return $this->ribValidated;
    }

    public function setRibValidated(?bool $ribValidated): self
    {
        $this->ribValidated = $ribValidated;

        return $this;
    }

    public function getActif(): ?int
    {
        return $this->actif;
    }

    public function setActif(?int $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getStatutJuridique(): ?string
    {
        return $this->statutJuridique;
    }

    public function setStatutJuridique(string $statutJuridique): self
    {
        $this->statutJuridique = $statutJuridique;

        return $this;
    }
    
}
