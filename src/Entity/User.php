<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idUser', type: 'integer')]
    private ?int $idUser = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'integer')]
    private ?int $age = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(name: 'phoneNumber', length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: 'integer', name: 'isAdmin')]
    private ?int $isAdmin = null;

    #[ORM\Column(type: 'integer', name: 'isCoach')]
    private ?int $isCoach = null;

    #[ORM\Column(name: 'photoProfile', length: 255)]
    private ?string $photoProfile = null;

    #[ORM\Column(length: 255)]
    private ?string $otp = null;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }
  
    public function setIdUser(int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getIsAdmin(): ?int
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(int $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getIsCoach(): ?int
    {
        return $this->isCoach;
    }

    public function setIsCoach(int $isCoach): static
    {
        $this->isCoach = $isCoach;

        return $this;
    }

    public function getPhotoProfile(): ?string
    {
        return $this->photoProfile;
    }

    public function setPhotoProfile(string $photoProfile): static
    {
        $this->photoProfile = $photoProfile;

        return $this;
    }

    public function getOtp(): ?string
    {
        return $this->otp;
    }

    public function setOtp(string $otp): static
    {
        $this->otp = $otp;

        return $this;
    }
}
