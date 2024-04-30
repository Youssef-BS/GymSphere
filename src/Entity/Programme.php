<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Programme
 *
 * @ORM\Table(name="programme", indexes={@ORM\Index(name="idUser", columns={"idUser"})})
 * @ORM\Entity
 */
class Programme
{
    /**
     * @var int
     *
     * @ORM\Column(name="idProgramme", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idprogramme;

    /**
     * @var string
     *
     * @ORM\Column(name="nomProgramme", type="string", length=255, nullable=false)
     */
    private $nomprogramme;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duree", type="date", nullable=false)
     */
    private $duree;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;


}
