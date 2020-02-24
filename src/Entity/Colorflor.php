<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Colorflor
 *
 * @ORM\Table(name="colorflor")
 * @ORM\Entity
 */
class Colorflor
{
    /**
     * @var int
     *
     * @ORM\Column(name="idColorFlor", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcolorflor;

    /**
     * @var string
     *
     * @ORM\Column(name="Color", type="string", length=45, nullable=false)
     */
    private $color;

    public function getIdcolorflor(): ?int
    {
        return $this->idcolorflor;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
    public function __toString()
    {
        return $this->color;
    }
}
