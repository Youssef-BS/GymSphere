<?php

namespace App\Entity;

<<<<<<< HEAD
use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idCommande = null;

    #[ORM\Column(type: 'integer')]
    private ?int $Total = null;

    #[ORM\Column(type: 'integer')]
    private ?int $commandeSt = 1;

 
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    private ?User $user;
    

    public function getidCommande(): ?int
    {
        return $this->idCommande;
    }
    public function setidCommande(int $idCommande): static
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->Total;
    }

    public function setTotal(int $Total): static
    {
        $this->Total = $Total;

        return $this;
    }

    public function getcommandeSt(): ?int
    {
        return $this->commandeSt;
    }

    public function setcommandeSt(int $commandeSt): static
    {
        $this->commandeSt = $commandeSt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}


=======
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="idProduit", columns={"idProduit"}), @ORM\Index(name="idUser", columns={"idUser"})})
 * @ORM\Entity
 */
class Commande
{
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
     * @ORM\Column(name="deplacement", type="string", length=255, nullable=false)
     */
    private $deplacement;

    /**
     * @var int
     *
     * @ORM\Column(name="nbProduit", type="integer", nullable=false)
     */
    private $nbproduit;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;

    /**
     * @var int
     *
     * @ORM\Column(name="idProduit", type="integer", nullable=false)
     */
    private $idproduit;


}
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
