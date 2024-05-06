<?php

namespace App\Entity;

<<<<<<< HEAD
use App\Repository\AbonnementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idAbonnement', type: 'integer')]
    private ?int $idAbonnement = null;

    #[ORM\Column(name: 'dateDebut', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: 'dateFin', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(name: 'prix')]
    private ?int $prix = null;

    #[ORM\Column(name: 'payment')]
    private ?bool $payment = null;

    #[ORM\Column(name: 'idUser')]
    private ?int $idUser = null;

    public function getIdAbonnement(): ?int
    {
        return $this->idAbonnement;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function isPayment(): ?bool
    {
        return $this->payment;
    }

    public function setPayment(bool $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }
=======
use Doctrine\ORM\Mapping as ORM;

/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement")
 * @ORM\Entity
 */
class Abonnement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idAbonnement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idabonnement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=false)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=false)
     */
    private $datefin;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
    private $prix;

    /**
     * @var bool
     *
     * @ORM\Column(name="payment", type="boolean", nullable=false)
     */
    private $payment = '0';


>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
}
