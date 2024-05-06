<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avis
 *
 * @ORM\Table(name="avis", indexes={@ORM\Index(name="idUser", columns={"idUser"})})
 * @ORM\Entity
 */
class Avis
{
    /**
     * @var int
     *
     * @ORM\Column(name="idAvis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idavis;

    /**
     * @var int
     *
     * @ORM\Column(name="avis", type="integer", nullable=false)
     */
    private $avis;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;


}
