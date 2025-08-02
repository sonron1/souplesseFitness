<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateNaissance = null;

    #[ORM\Column]
    private ?\DateTime $dateInscription = null;

    #[ORM\Column]
    private ?bool $actif = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'client')]
    private Collection $commandes;

    #[ORM\Column(length: 50)]
    private ?string $numeroMembre = null;

    #[ORM\Column]
    private ?int $pointsFidelite = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $profession = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $contactUrgence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateDebutAbonnement = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateFinAbonnement = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typeAbonnement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateVisite = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Coach $coach = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Abonnement $abonnementActuel = null;

    /**
     * @var Collection<int, SeanceCoaching>
     */
    #[ORM\OneToMany(targetEntity: SeanceCoaching::class, mappedBy: 'client')]
    private Collection $seanceCoachings;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\ManyToMany(targetEntity: Cours::class, inversedBy: 'clients')]
    private Collection $coursInscrits;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->seanceCoachings = new ArrayCollection();
        $this->coursInscrits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTime $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateInscription(): ?\DateTime
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTime $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }

        return $this;
    }

    public function getNumeroMembre(): ?string
    {
        return $this->numeroMembre;
    }

    public function setNumeroMembre(string $numeroMembre): static
    {
        $this->numeroMembre = $numeroMembre;

        return $this;
    }

    public function getPointsFidelite(): ?int
    {
        return $this->pointsFidelite;
    }

    public function setPointsFidelite(int $pointsFidelite): static
    {
        $this->pointsFidelite = $pointsFidelite;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getContactUrgence(): ?string
    {
        return $this->contactUrgence;
    }

    public function setContactUrgence(?string $contactUrgence): static
    {
        $this->contactUrgence = $contactUrgence;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDateDebutAbonnement(): ?\DateTime
    {
        return $this->dateDebutAbonnement;
    }

    public function setDateDebutAbonnement(?\DateTime $dateDebutAbonnement): static
    {
        $this->dateDebutAbonnement = $dateDebutAbonnement;

        return $this;
    }

    public function getDateFinAbonnement(): ?\DateTime
    {
        return $this->dateFinAbonnement;
    }

    public function setDateFinAbonnement(?\DateTime $dateFinAbonnement): static
    {
        $this->dateFinAbonnement = $dateFinAbonnement;

        return $this;
    }

    public function getTypeAbonnement(): ?string
    {
        return $this->typeAbonnement;
    }

    public function setTypeAbonnement(?string $typeAbonnement): static
    {
        $this->typeAbonnement = $typeAbonnement;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getDateVisite(): ?\DateTime
    {
        return $this->dateVisite;
    }

    public function setDateVisite(?\DateTime $dateVisite): static
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(?Coach $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    public function getAbonnementActuel(): ?Abonnement
    {
        return $this->abonnementActuel;
    }

    public function setAbonnementActuel(?Abonnement $abonnementActuel): static
    {
        $this->abonnementActuel = $abonnementActuel;

        return $this;
    }

    /**
     * @return Collection<int, SeanceCoaching>
     */
    public function getSeanceCoachings(): Collection
    {
        return $this->seanceCoachings;
    }

    public function addSeanceCoaching(SeanceCoaching $seanceCoaching): static
    {
        if (!$this->seanceCoachings->contains($seanceCoaching)) {
            $this->seanceCoachings->add($seanceCoaching);
            $seanceCoaching->setClient($this);
        }

        return $this;
    }

    public function removeSeanceCoaching(SeanceCoaching $seanceCoaching): static
    {
        if ($this->seanceCoachings->removeElement($seanceCoaching)) {
            // set the owning side to null (unless already changed)
            if ($seanceCoaching->getClient() === $this) {
                $seanceCoaching->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCoursInscrits(): Collection
    {
        return $this->coursInscrits;
    }

    public function addCoursInscrit(Cours $coursInscrit): static
    {
        if (!$this->coursInscrits->contains($coursInscrit)) {
            $this->coursInscrits->add($coursInscrit);
        }

        return $this;
    }

    public function removeCoursInscrit(Cours $coursInscrit): static
    {
        $this->coursInscrits->removeElement($coursInscrit);

        return $this;
    }
}
