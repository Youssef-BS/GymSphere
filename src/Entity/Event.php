<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/^[A-Z]/',
        message: 'Le nom doit commencer par une majuscule.'
    )]
    #[Assert\NotBlank(message: 'Le nom est requis.')]
    #[Assert\Length(
        max:25,
        min:6,
        minMessage: 'Le nom doit faire au moins 6 caractères.',
        maxMessage: 'Le nom ne doit faire plus que 25 caractères.')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La description est requise.',)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Durée est requise.')]
    private ?string $duree = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Type est requise.')]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(
        value: 'today',
        message: 'La date limite d\'inscription doit être supérieure à la date d\'aujourd\'hui.'
    )]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_participants = 0;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le nombre maximal des participant est requis.')]
    #[Assert\Type(
        type: 'integer',
        message: 'Le nombre maximal doit être un nombre .'
    )]
    #[Assert\GreaterThan(value:0,message:'Le nombre maximal doit être superieue à 0 .')]
    private ?int $nb_max = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Localisation est requise.')]
    private ?string $localisation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Status est requise.')]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[Assert\NotBlank(message: 'Program est requis.')]
    private ?Program $Program = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nb_participants;
    }

    public function setNbParticipants(?int $nb_participants): static
    {
        $this->nb_participants = $nb_participants;

        return $this;
    }

    public function getNbMax(): ?int
    {
        return $this->nb_max;
    }

    public function setNbMax(int $nb_max): static
    {
        $this->nb_max = $nb_max;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getProgram(): ?Program
    {
        return $this->Program;
    }

    public function setProgram(?Program $Program): static
    {
        $this->Program = $Program;

        return $this;
    }
}
