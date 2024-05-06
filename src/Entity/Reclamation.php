<?php

namespace App\Entity;

<<<<<<< HEAD
use Symfony\Component\Validator\Constraints as Assert;

=======
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
<<<<<<< HEAD
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="idUser", columns={"iduser"})})
=======
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="idPoduit", columns={"idPoduit"}), @ORM\Index(name="idUser", columns={"idUser"}), @ORM\Index(name="idGym", columns={"idGym"})})
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
<<<<<<< HEAD
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
     * @Assert\Length(max=255, maxMessage="Le titre ne peut pas dépasser {{ limit }} caractères")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le type ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="Le type ne peut pas dépasser {{ limit }} caractères")
     */
    private $type;
=======
     * @ORM\Column(name="idReclamation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreclamation;
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
<<<<<<< HEAD
     * @Assert\NotBlank(message="La description ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="La description ne peut pas dépasser {{ limit }} caractères")
=======
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
     */
    private $description;

    /**
<<<<<<< HEAD
     * @var \DateTime
     *
     * @ORM\Column(name="date_reclamation", type="date", nullable=false)
     * @Assert\NotBlank(message="La date de réclamation ne peut pas être vide")
     */
    private $dateReclamation;
=======
     * @var int|null
     *
     * @ORM\Column(name="idPoduit", type="integer", nullable=true)
     */
    private $idpoduit;
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681

    /**
     * @var int|null
     *
<<<<<<< HEAD
     * @ORM\Column(name="iduser", type="integer", nullable=true)
=======
     * @ORM\Column(name="idGym", type="integer", nullable=true)
     */
    private $idgym;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idUser", type="integer", nullable=true)
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
     */
    private $iduser;


<<<<<<< HEAD



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->dateReclamation;
    }

    public function setDateReclamation(\DateTimeInterface $dateReclamation): self
    {
        $this->dateReclamation = $dateReclamation;

        return $this;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(?int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }





=======
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
}
