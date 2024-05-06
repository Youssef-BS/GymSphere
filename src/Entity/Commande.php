<?php

namespace App\Entity;

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


