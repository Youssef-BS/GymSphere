<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appartient
 *
 * @ORM\Table(name="appartient", indexes={@ORM\Index(name="idGym", columns={"idGym"}), @ORM\Index(name="idUser", columns={"idUser"}), @ORM\Index(name="idAbonnement", columns={"idAbonnement"})})
 * @ORM\Entity
 */
class Appartient
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
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;

    /**
     * @var int
     *
     * @ORM\Column(name="idGym", type="integer", nullable=false)
     */
    private $idgym;

    /**
     * @var int
     *
     * @ORM\Column(name="idAbonnement", type="integer", nullable=false)
     */
    private $idabonnement;


}
