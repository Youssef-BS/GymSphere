<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calandrier
 *
 * @ORM\Table(name="calandrier", indexes={@ORM\Index(name="idGym", columns={"idGym"})})
 * @ORM\Entity
 */
class Calandrier
{


     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gym", inversedBy="calandriers")
     * @ORM\JoinColumn(name="idGym", referencedColumnName="idGym")
     */
    private $gym;

    /**
     * @var int
     *
     * @ORM\Column(name="idCalandrier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcalandrier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_activite", type="date", nullable=false)
     */
    private $dateActivite;

    /**
     * @var string
     *
     * @ORM\Column(name="typeActivite", type="string", length=255, nullable=false)
     */
    private $typeactivite;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="heureFermuture", type="string", length=255, nullable=false)
     */
    private $heurefermuture;

    /**
     * @var int
     *
     * @ORM\Column(name="idGym", type="integer", nullable=false)
     */
    private $idgym;


    // Getters

    public function getIdCalandrier(): ?int
    {
        return $this->idcalandrier;
    }

    public function getDateActivite(): ?\DateTimeInterface
    {
        return $this->dateActivite;
    }

    public function getTypeActivite(): ?string
    {
        return $this->typeactivite;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getHeureFermuture(): ?string
    {
        return $this->heurefermuture;
    }

    public function getIdGym(): ?int
    {
        return $this->idgym;
    }

    // Setters

    public function setDateActivite(\DateTimeInterface $dateActivite): self
    {
        $this->dateActivite = $dateActivite;

        return $this;
    }

    public function setTypeActivite(string $typeactivite): self
    {
        $this->typeactivite = $typeactivite;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setHeureFermuture(string $heurefermuture): self
    {
        $this->heurefermuture = $heurefermuture;

        return $this;
    }

    public function setIdGym(int $idgym): self
    {
        $this->idgym = $idgym;

        return $this;
    }

    public function getGym(): ?Gym
    {
        return $this->gym;
    }

    public function setGym(?Gym $gym): self
    {
        $this->gym = $gym;

        return $this;
    }
    
}
