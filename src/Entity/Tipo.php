<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipo
 *
 * @ORM\Table(name="tipo")
 * @ORM\Entity
 */
class Tipo
{
    /**
     * @var int
     *
     * @ORM\Column(name="idTipo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtipo;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=85, nullable=false)
     */
    private $nombre;

    public function getIdtipo(): ?int
    {
        return $this->idtipo;
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
    public function __toString()
    {
        return $this->nombre;
    }
}
