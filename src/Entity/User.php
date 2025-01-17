<?php

namespace App\Entity;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"string",length: 180, unique: true)]
    private ?string $email ;

    #[ORM\Column(type:"string",length: 180)]
    private ?string $name ;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Inscription::class, mappedBy: 'user')]
    private Collection $inscriptions;

    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'User')]
    private Collection $participations;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
=======
use Symfony\Component\Security\Core\User\UserInterface;
=======
>>>>>>> 649ef9c620e35f87ee5e3746f7e798e948fc7cc4

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
<<<<<<< HEAD
class User implements UserInterface
=======
class User
>>>>>>> 649ef9c620e35f87ee5e3746f7e798e948fc7cc4
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

<<<<<<< HEAD
    #[ORM\Column(length: 255)]
    private ?string $otp = null;

=======
>>>>>>> 649ef9c620e35f87ee5e3746f7e798e948fc7cc4
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
<<<<<<< HEAD
>>>>>>> 81688ff31e36db63b702e05ba73f5478ffdd725f
=======
>>>>>>> 649ef9c620e35f87ee5e3746f7e798e948fc7cc4
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
<<<<<<< HEAD
<<<<<<< HEAD
    
    
     /**
     * @param mixed $name
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * @param mixed 
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
=======

    public function getPassword(): ?string
>>>>>>> 81688ff31e36db63b702e05ba73f5478ffdd725f
=======

    public function getPassword(): ?string
>>>>>>> 649ef9c620e35f87ee5e3746f7e798e948fc7cc4
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setUser($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getUser() === $this) {
                $inscription->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setUser($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getUser() === $this) {
                $participation->setUser(null);
            }
        }

        return $this;
=======
=======
>>>>>>> 649ef9c620e35f87ee5e3746f7e798e948fc7cc4
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
<<<<<<< HEAD
    
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

    public function getRoles()
    {
        // Define user roles here, if needed
        return ['ROLE_USER'];
    }
    
    // Implement other methods required by UserInterface
    public function getUsername()
    {
        return $this->email; // Assuming email is the username
    }

    public function getSalt()
    {
        // Leave this empty if not using plain passwords
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
>>>>>>> 81688ff31e36db63b702e05ba73f5478ffdd725f
    }
}
=======

        return $this;
    }
}
>>>>>>> 649ef9c620e35f87ee5e3746f7e798e948fc7cc4
=======
=======
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iduser;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
<<<<<<< HEAD
=======
     * @var string|null
     *
     * @ORM\Column(name="photoProfile", type="string", length=255, nullable=true)
     */
    private $photoprofile;

    /**
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
<<<<<<< HEAD
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=255, nullable=false)
=======
     * @var int
     *
     * @ORM\Column(name="phoneNumber", type="integer", nullable=false)
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
     */
    private $phonenumber;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isAdmin", type="boolean", nullable=true)
     */
    private $isadmin = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isCoach", type="boolean", nullable=true)
     */
    private $iscoach = '0';

    /**
     * @var string|null
     *
<<<<<<< HEAD
     * @ORM\Column(name="photoProfile", type="string", length=255, nullable=true, options={"default"="https://i.pinimg.com/236x/fa/d5/e7/fad5e79954583ad50ccb3f16ee64f66d.jpg"})
     */
    private $photoprofile = 'https://i.pinimg.com/236x/fa/d5/e7/fad5e79954583ad50ccb3f16ee64f66d.jpg';

    /**
     * @var string|null
     *
     * @ORM\Column(name="otp", type="string", length=4, nullable=true)
     */
    private $otp;


}
>>>>>>> 0f9be098c09a370d9b7246eec13ee77203b60875
=======
     * @ORM\Column(name="otp", type="string", length=255, nullable=true)
     */
    private $otp;

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhotoprofile(): ?string
    {
        return $this->photoprofile;
    }

    public function setPhotoprofile(?string $photoprofile): self
    {
        $this->photoprofile = $photoprofile;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhonenumber(): ?int
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(int $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getIsadmin(): ?bool
    {
        return $this->isadmin;
    }

    public function setIsadmin(?bool $isadmin): self
    {
        $this->isadmin = $isadmin;

        return $this;
    }

    public function getIscoach(): ?bool
    {
        return $this->iscoach;
    }

    public function setIscoach(?bool $iscoach): self
    {
        $this->iscoach = $iscoach;

        return $this;
    }

    public function getOtp(): ?string
    {
        return $this->otp;
    }

    public function setOtp(?string $otp): self
    {
        $this->otp = $otp;

        return $this;
    }
}
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
