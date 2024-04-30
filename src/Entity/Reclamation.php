<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="idPoduit", columns={"idPoduit"}), @ORM\Index(name="idUser", columns={"idUser"}), @ORM\Index(name="idGym", columns={"idGym"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idReclamation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idPoduit", type="integer", nullable=true)
     */
    private $idpoduit;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idGym", type="integer", nullable=true)
     */
    private $idgym;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idUser", type="integer", nullable=true)
     */
    private $iduser;


}
