<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gym
 *
 * @ORM\Table(name="gym")
 * @ORM\Entity
 */
class Gym
{
    /**
     * @var int
     *
     * @ORM\Column(name="idGym", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idgym;

    /**
     * @var string
     *
     * @ORM\Column(name="nomGym", type="string", length=255, nullable=false)
     */
    private $nomgym;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="photoGym", type="string", length=255, nullable=false)
     */
    private $photogym;

    public function getIdgym(): ?int
    {
        return $this->idgym;
    }

    public function setIdgym(int $idgym): self
    {
        $this->idgym = $idgym;

        return $this;
    }

    public function getNomgym(): ?string
    {
        return $this->nomgym;
    }

    public function setNomgym(string $nomgym): self
    {
        $this->nomgym = $nomgym;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhotogym(): ?string
    {
        return $this->photogym;
    }

    public function setPhotogym(string $photogym): self
    {
        $this->photogym = $photogym;

        return $this;
    }




    
}
