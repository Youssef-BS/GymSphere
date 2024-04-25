<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 

/**
 * Repondre
 *
 * @ORM\Table(name="repondre")
 * @ORM\Entity
 */
class Repondre
{


    /**
 * @ORM\ManyToOne(targetEntity="App\Entity\Reclamation")
 * @ORM\JoinColumn(name="idreclamation", referencedColumnName="id")
 */
private $reclamation;

// Getter et setter pour $reclamation
public function getReclamation(): ?Reclamation
{
    return $this->reclamation;
}

public function setReclamation(?Reclamation $reclamation): self
{
    $this->reclamation = $reclamation;

    return $this;
}


// /**
//      * @ORM\ManyToOne(targetEntity="App\Entity\Reclamation")
//      * @ORM\JoinColumn(name="idreclamation", referencedColumnName="id")
//      */
//     private $reclamation;

//     // Getters and Setters for reclamation
//     public function getReclamation(): ?Reclamation
//     {
//         return $this->reclamation;
//     }

//     public function setReclamation(?Reclamation $reclamation): self
//     {
//         $this->reclamation = $reclamation;

//         return $this;
//     }





 /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le titre ne peut pas être vide")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La réponse ne peut pas être vide")
     */
    private $reponse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reponse", type="date", nullable=false)
     * @Assert\NotBlank(message="La date de réponse ne peut pas être vide")
     * @Assert\Type("\DateTimeInterface")
     */
    private $dateReponse;

    /**
     * @var int
     *
     * @ORM\Column(name="iduser", type="integer", nullable=false)
     * @Assert\NotBlank(message="L'ID utilisateur ne peut pas être vide")
     */
    private $iduser;

    /**
     * @var int
     *
     * @ORM\Column(name="idreclamation", type="integer", nullable=false)
     * @Assert\NotBlank(message="L'ID réclamation ne peut pas être vide")
     */
    private $idreclamation;

    // Getters and Setters for id
    public function getId(): ?int
    {
        return $this->id;
    }

    // No setId() method as id is auto-generated

    // Getters and Setters for titre
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    // Getters and Setters for reponse
    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    // Getters and Setters for dateReponse
    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    // Getters and Setters for iduser
    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    // Getters and Setters for idreclamation
    public function getIdreclamation(): ?int
    {
        return $this->idreclamation;
    }

    public function setIdreclamation(int $idreclamation): self
    {
        $this->idreclamation = $idreclamation;

        return $this;
    }
}
