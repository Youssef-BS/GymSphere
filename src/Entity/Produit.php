<?php

namespace App\Entity;

<<<<<<< HEAD
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idProduit', type: 'integer')]

    private ?int $idProduit = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 5, minMessage: "The product name must be at least {{ limit }} characters long")]
    private ?string $nomProduit = null;

 

    #[ORM\Column(type: 'integer')]
    #[Assert\GreaterThan(value: 10, message: "The product price must be greater than $10")]
    private ?int $prixProduit = null;

    #[ORM\Column(length: 255)]
    private ?string $photoProduit = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantiteProduit= 1;

    public function getidProduit(): ?int
    {
        return $this->idProduit;
    }
  
    public function setidProduit(int $idProduit): static
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getnomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setnomProduit(string $nomProduit): static
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getprixProduit(): ?int
    {
        return $this->prixProduit;
    }

    public function setprixProduit(int $prixProduit): static
    {
        $this->prixProduit = $prixProduit;

        return $this;
    }

    public function getphotoProduit(): ?string
    {
        return $this->photoProduit;
    }

    public function setphotoProduit(string $photoProduit): static
    {
        $this->photoProduit = $photoProduit;

        return $this;
    }


    public function getquantiteProduit(): ?int
    {
        return $this->quantiteProduit;
    }

    public function setquantiteProduit(int $quantiteProduit): static
    {
        $this->quantiteProduit = $quantiteProduit;

        return $this;
    }
}

=======
use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="idProduit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idproduit;

    /**
     * @var string
     *
     * @ORM\Column(name="nomProduit", type="string", length=255, nullable=false)
     */
    private $nomproduit;

    /**
     * @var string
     *
     * @ORM\Column(name="photoProduit", type="string", length=255, nullable=false)
     */
    private $photoproduit;


}
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
