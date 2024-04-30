<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="idProduit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idproduit;

    /**
     * @var string
     *
     * @ORM\Column(name="nomProduit", type="string", length=255, nullable=false)
     */
    private $nomproduit;

    /**
     * @var string
     *
     * @ORM\Column(name="photoProduit", type="string", length=255, nullable=false)
     */
    private $photoproduit;


}
