<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
class Program
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Date limite est requis.')]
    #[Assert\GreaterThan(
        value: 'today',
        message: 'La date limite d\'inscription doit être supérieure à la date d\'aujourd\'hui.'
    )]
    private ?\DateTimeInterface $registration_deadline = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le prix est requis.')]
    #[Assert\Type(
        type: 'float',
        message: 'Le prix doit être un nombre décimal.'
    )]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $imgsrc = null;

    #[ORM\OneToMany(targetEntity: Exercice::class, mappedBy: 'Program')]
    private Collection $exercices;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'Program')]
    private Collection $events;

    #[ORM\OneToMany(targetEntity: Inscription::class, mappedBy: 'program')]
    private Collection $inscriptions;

    public function __construct()
    {
        $this->exercices = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
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

    public function getRegistrationDeadline(): ?\DateTimeInterface
    {
        return $this->registration_deadline;
    }

    public function setRegistrationDeadline(\DateTimeInterface $registration_deadline): static
    {
        $this->registration_deadline = $registration_deadline;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImgsrc(): ?string
    {
        return $this->imgsrc;
    }

    public function setImgsrc(string $imgsrc): static
    {
        $this->imgsrc = $imgsrc;

        return $this;
    }

    /**
     * @return Collection<int, Exercice>
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices->add($exercice);
            $exercice->setProgram($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercices->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getProgram() === $this) {
                $exercice->setProgram(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setProgram($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getProgram() === $this) {
                $event->setProgram(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setProgram($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getProgram() === $this) {
                $inscription->setProgram(null);
            }
        }

        return $this;
    }
}
