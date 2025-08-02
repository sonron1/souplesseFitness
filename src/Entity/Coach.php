<?php

namespace App\Entity;

use App\Repository\CoachRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoachRepository::class)]
class Coach
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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $specialites = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column]
    private ?\DateTime $dateEmbauche = null;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'coach')]
    private Collection $clients;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'coach')]
    private Collection $cours;

    /**
     * @var Collection<int, SeanceCoaching>
     */
    #[ORM\OneToMany(targetEntity: SeanceCoaching::class, mappedBy: 'coach')]
    private Collection $seanceCoachings;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->seanceCoachings = new ArrayCollection();
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

    public function getSpecialites(): ?string
    {
        return $this->specialites;
    }

    public function setSpecialites(?string $specialites): static
    {
        $this->specialites = $specialites;

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

    public function getDateEmbauche(): ?\DateTime
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTime $dateEmbauche): static
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setCoach($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getCoach() === $this) {
                $client->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setCoach($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getCoach() === $this) {
                $cour->setCoach(null);
            }
        }

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
            $seanceCoaching->setCoach($this);
        }

        return $this;
    }

    public function removeSeanceCoaching(SeanceCoaching $seanceCoaching): static
    {
        if ($this->seanceCoachings->removeElement($seanceCoaching)) {
            // set the owning side to null (unless already changed)
            if ($seanceCoaching->getCoach() === $this) {
                $seanceCoaching->setCoach(null);
            }
        }

        return $this;
    }
}
