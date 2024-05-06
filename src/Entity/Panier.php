<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idPanier', type: 'integer')]
    private ?int $idPanier = null;

    
    #[ORM\Column(name: 'idUser',type: 'integer')]
    private ?int $idUser = null;

    


    #[ORM\Column(name: 'idProduit' ,type: 'integer')]
    private ?int $idProduit = null;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: 'idProduit', referencedColumnName: 'idProduit')]
    private ?Produit $produit;

    #[ORM\Column(type: 'integer')]
    private ?int $status = 1;

    public function getidPanier(): ?int
    {
        return $this->idPanier;
    }
  
    public function setidPanier(int $idPanier): static
    {
        $this->idPanier = $idPanier;

        return $this;
    }

    public function getidUser(): ?int
    {
        return $this->idUser;
    }
  
    public function setidUser(int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }


    public function getstatus(): ?int
    {
        return $this->status;
    }
  
    public function setstatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
      
    }

}