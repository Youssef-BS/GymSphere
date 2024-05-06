<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement", indexes={@ORM\Index(name="idProgramme", columns={"idProgramme"})})
 * @ORM\Entity
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idEvent", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEvent", type="date", nullable=false)
     */
    private $dateevent;

    /**
     * @var int
     *
     * @ORM\Column(name="nbMax", type="integer", nullable=false)
     */
    private $nbmax;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=false)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="typeEvent", type="string", length=255, nullable=false)
     */
    private $typeevent;

    /**
     * @var int
     *
     * @ORM\Column(name="idProgramme", type="integer", nullable=false)
     */
    private $idprogramme;


}
