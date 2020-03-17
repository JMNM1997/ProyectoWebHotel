<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Habitacion
 *
 * @ORM\Table(name="habitacion")
 * @ORM\Entity
 */
class Habitacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="codHabitacion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codhabitacion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=45, nullable=false)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="planta", type="string", length=45, nullable=false)
     */
    private $planta;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=45, nullable=false)
     */
    private $imagen;

    /**
     * @var string
     *
     * @ORM\Column(name="extras", type="string", length=45, nullable=false)
     */
    private $extras;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float", precision=10, scale=0, nullable=false)
     */
    private $precio;

    public function getCodhabitacion(): ?int
    {
        return $this->codhabitacion;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getPlanta(): ?string
    {
        return $this->planta;
    }

    public function setPlanta(string $planta): self
    {
        $this->planta = $planta;

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

    public function getExtras(): ?string
    {
        return $this->extras;
    }

    public function setExtras(string $extras): self
    {
        $this->extras = $extras;

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


}
