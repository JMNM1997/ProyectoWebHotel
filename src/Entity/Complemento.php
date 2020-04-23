<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Complemento
 *
 * @ORM\Table(name="complemento")
 * @ORM\Entity
 */
class Complemento
{
    /**
     * @var int
     *
     * @ORM\Column(name="idComplemento", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcomplemento;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=true)
     */
    private $nombre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Habitacion", mappedBy="complementoIdcomplemento")
     */
    private $habitacionCodhabitacion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->habitacionCodhabitacion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdcomplemento(): ?int
    {
        return $this->idcomplemento;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Habitacion[]
     */
    public function getHabitacionCodhabitacion(): Collection
    {
        return $this->habitacionCodhabitacion;
    }

    public function addHabitacionCodhabitacion(Habitacion $habitacionCodhabitacion): self
    {
        if (!$this->habitacionCodhabitacion->contains($habitacionCodhabitacion)) {
            $this->habitacionCodhabitacion[] = $habitacionCodhabitacion;
            $habitacionCodhabitacion->addComplementoIdcomplemento($this);
        }

        return $this;
    }

    public function removeHabitacionCodhabitacion(Habitacion $habitacionCodhabitacion): self
    {
        if ($this->habitacionCodhabitacion->contains($habitacionCodhabitacion)) {
            $this->habitacionCodhabitacion->removeElement($habitacionCodhabitacion);
            $habitacionCodhabitacion->removeComplementoIdcomplemento($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nombre;
    }
}
