<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $emergencyContact = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $emergencyPhone = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $fitnessGoals = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $experienceLevel = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $medicalConditions = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $subscribeNewsletter = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isVerified = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    // Nouveau champ pour le suivi des connexions
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

    // Nouveau champ pour activer/désactiver un utilisateur
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isActive = true;

    public function __construct()
    {
        $this->roles = ['ROLE_CLIENT']; // Rôle par défaut
        $this->createdAt = new \DateTimeImmutable();
        $this->isActive = true;
    }

    // Getters et setters existants...
    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Garantit que chaque utilisateur a au moins ROLE_USER
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Nettoie les données sensibles temporaires
    }

    // Getters et setters pour les autres propriétés...
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getEmergencyContact(): ?string
    {
        return $this->emergencyContact;
    }

    public function setEmergencyContact(?string $emergencyContact): static
    {
        $this->emergencyContact = $emergencyContact;
        return $this;
    }

    public function getEmergencyPhone(): ?string
    {
        return $this->emergencyPhone;
    }

    public function setEmergencyPhone(?string $emergencyPhone): static
    {
        $this->emergencyPhone = $emergencyPhone;
        return $this;
    }

    public function getFitnessGoals(): ?string
    {
        return $this->fitnessGoals;
    }

    public function setFitnessGoals(?string $fitnessGoals): static
    {
        $this->fitnessGoals = $fitnessGoals;
        return $this;
    }

    public function getExperienceLevel(): ?string
    {
        return $this->experienceLevel;
    }

    public function setExperienceLevel(?string $experienceLevel): static
    {
        $this->experienceLevel = $experienceLevel;
        return $this;
    }

    public function getMedicalConditions(): ?string
    {
        return $this->medicalConditions;
    }

    public function setMedicalConditions(?string $medicalConditions): static
    {
        $this->medicalConditions = $medicalConditions;
        return $this;
    }

    public function isSubscribeNewsletter(): bool
    {
        return $this->subscribeNewsletter;
    }

    public function setSubscribeNewsletter(bool $subscribeNewsletter): static
    {
        $this->subscribeNewsletter = $subscribeNewsletter;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    // Méthodes utilitaires pour les rôles
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('ROLE_ADMIN');
    }

    public function isCoach(): bool
    {
        return $this->hasRole('ROLE_COACH');
    }

    public function isClient(): bool
    {
        return $this->hasRole('ROLE_CLIENT');
    }

    public function getMainRole(): string
    {
        if ($this->isAdmin()) return 'Administrateur';
        if ($this->isCoach()) return 'Coach';
        if ($this->isClient()) return 'Client';
        return 'Utilisateur';
    }

    public function getRoleBadgeClass(): string
    {
        if ($this->isAdmin()) return 'bg-danger';
        if ($this->isCoach()) return 'bg-warning';
        if ($this->isClient()) return 'bg-success';
        return 'bg-secondary';
    }
}
