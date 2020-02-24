<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**

 * Producto
 *
 * @ORM\Table(name="producto")
 * @ORM\Entity
 */
class Producto
{
    /** 
     * @var int
     *
     * @ORM\Column(name="idProducto", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idproducto;

    /** 
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /** 
     * @var string
     *
     * @ORM\Column(name="Descripcion", type="string", length=500, nullable=false)
     */
    private $descripcion;

    /**
     * @var float
     *
     * @ORM\Column(name="Precio", type="float", precision=10, scale=0, nullable=false)
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=250, nullable=false)
     */
    private $imagen;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usomedico", inversedBy="productoIdproducto")
     * @ORM\JoinTable(name="producto_has_usomedico",
     *   joinColumns={
     *     @ORM\JoinColumn(name="producto_idProducto", referencedColumnName="idProducto")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="usomedico_idUsoMedico", referencedColumnName="idUsoMedico")
     *   }
     * )
     */
    private $usomedicoIdusomedico;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usomedicoIdusomedico = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdproducto(): ?int
    {
        return $this->idproducto;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * @return Collection|Usomedico[]
     */
    public function getUsomedicoIdusomedico(): Collection
    {
        return $this->usomedicoIdusomedico;
    }

    public function addUsomedicoIdusomedico(Usomedico $usomedicoIdusomedico): self
    {
        if (!$this->usomedicoIdusomedico->contains($usomedicoIdusomedico)) {
            $this->usomedicoIdusomedico[] = $usomedicoIdusomedico;
        }

        return $this;
    }

    public function removeUsomedicoIdusomedico(Usomedico $usomedicoIdusomedico): self
    {
        if ($this->usomedicoIdusomedico->contains($usomedicoIdusomedico)) {
            $this->usomedicoIdusomedico->removeElement($usomedicoIdusomedico);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
