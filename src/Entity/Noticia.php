<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Noticia
 *
 * @ORM\Table(name="noticia", indexes={@ORM\Index(name="fk_Noticia_Categoria1_idx", columns={"Categoria_idCategoria"})})
 * @ORM\Entity
 */
class Noticia
{
    /**
     * @var int
     *
     * @ORM\Column(name="codNoticia", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codnoticia;

    /**
     * @var string
     *
     * @ORM\Column(name="titular", type="string", length=95, nullable=false)
     */
    private $titular;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=false)
     */
    private $imagen;

    /**
     * @var \Categoria
     *
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Categoria_idCategoria", referencedColumnName="idCategoria")
     * })
     */
    private $categoriaIdcategoria;

    public function getCodnoticia(): ?int
    {
        return $this->codnoticia;
    }

    public function getTitular(): ?string
    {
        return $this->titular;
    }

    public function setTitular(string $titular): self
    {
        $this->titular = $titular;

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

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getCategoriaIdcategoria(): ?Categoria
    {
        return $this->categoriaIdcategoria;
    }

    public function setCategoriaIdcategoria(?Categoria $categoriaIdcategoria): self
    {
        $this->categoriaIdcategoria = $categoriaIdcategoria;

        return $this;
    }
    public function __toString()
    {
        return $this->titular;
    }
}
