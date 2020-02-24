<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Parteutil
 *
 * @ORM\Table(name="parteutil")
 * @ORM\Entity
 */
class Parteutil
{
    /**
     * @var int
     *
     * @ORM\Column(name="idParteUtil", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idparteutil;

    /**
     * @var string
     *
     * @ORM\Column(name="Parte", type="string", length=45, nullable=false)
     */
    private $parte;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Planta", mappedBy="parteutilIdparteutil")
     */
    private $plantaIdplanta;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plantaIdplanta = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdparteutil(): ?int
    {
        return $this->idparteutil;
    }

    public function getParte(): ?string
    {
        return $this->parte;
    }

    public function setParte(string $parte): self
    {
        $this->parte = $parte;

        return $this;
    }

    /**
     * @return Collection|Planta[]
     */
    public function getPlantaIdplanta(): Collection
    {
        return $this->plantaIdplanta;
    }

    public function addPlantaIdplantum(Planta $plantaIdplantum): self
    {
        if (!$this->plantaIdplanta->contains($plantaIdplantum)) {
            $this->plantaIdplanta[] = $plantaIdplantum;
            $plantaIdplantum->addParteutilIdparteutil($this);
        }

        return $this;
    }

    public function removePlantaIdplantum(Planta $plantaIdplantum): self
    {
        if ($this->plantaIdplanta->contains($plantaIdplantum)) {
            $this->plantaIdplanta->removeElement($plantaIdplantum);
            $plantaIdplantum->removeParteutilIdparteutil($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->parte;
    }
}
