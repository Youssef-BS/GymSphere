<?php

namespace App\Entity;

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
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=255, nullable=false)
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
