<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="idProduit", columns={"idProduit"}), @ORM\Index(name="idUser", columns={"idUser"})})
 * @ORM\Entity
 */
class Commande
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
     * @var string
     *
     * @ORM\Column(name="deplacement", type="string", length=255, nullable=false)
     */
    private $deplacement;

    /**
     * @var int
     *
     * @ORM\Column(name="nbProduit", type="integer", nullable=false)
     */
    private $nbproduit;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;

    /**
     * @var int
     *
     * @ORM\Column(name="idProduit", type="integer", nullable=false)
     */
    private $idproduit;


}
