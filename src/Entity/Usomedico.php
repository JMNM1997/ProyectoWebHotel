<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Usomedico
 *
 * @ORM\Table(name="usomedico")
 * @ORM\Entity
 */
class Usomedico
{
    /**
     * @var int
     *
     * @ORM\Column(name="idUsoMedico", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idusomedico;

    /**
     * @var string
     *
     * @ORM\Column(name="Uso", type="string", length=150, nullable=false)
     */
    private $uso;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Planta", mappedBy="usomedicoIdusomedico")
     */
    private $plantaIdplanta;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Producto", inversedBy="productoIdproducto")
     * @ORM\JoinTable(name="producto_has_usomedico",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Producto_idProducto", referencedColumnName="idUsoMedico")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="UsoMedico_idUsoMedico", referencedColumnName="idProducto")
     *   }
     * )
     */
    private $usomedicoIdusomedico;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plantaIdplanta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usomedicoIdusomedico = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdusomedico(): ?int
    {
        return $this->idusomedico;
    }

    public function getUso(): ?string
    {
        return $this->uso;
    }

    public function setUso(string $uso): self
    {
        $this->uso = $uso;

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
            $plantaIdplantum->addUsomedicoIdusomedico($this);
        }

        return $this;
    }

    public function removePlantaIdplantum(Planta $plantaIdplantum): self
    {
        if ($this->plantaIdplanta->contains($plantaIdplantum)) {
            $this->plantaIdplanta->removeElement($plantaIdplantum);
            $plantaIdplantum->removeUsomedicoIdusomedico($this);
        }

        return $this;
    }

    /**
     * @return Collection|Producto[]
     */
    public function getUsomedicoIdusomedico(): Collection
    {
        return $this->usomedicoIdusomedico;
    }

    public function addUsomedicoIdusomedico(Producto $usomedicoIdusomedico): self
    {
        if (!$this->usomedicoIdusomedico->contains($usomedicoIdusomedico)) {
            $this->usomedicoIdusomedico[] = $usomedicoIdusomedico;
        }

        return $this;
    }

    public function removeUsomedicoIdusomedico(Producto $usomedicoIdusomedico): self
    {
        if ($this->usomedicoIdusomedico->contains($usomedicoIdusomedico)) {
            $this->usomedicoIdusomedico->removeElement($usomedicoIdusomedico);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->uso;
    }
}
