<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Planta
 *
 * @ORM\Table(name="planta", indexes={@ORM\Index(name="fk_Planta_ColorFlor_idx", columns={"ColorFlor_idColorFlor"})})
 * @ORM\Entity
 */
class Planta
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPlanta", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idplanta;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=45, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Descripcion", type="string", length=500, nullable=false)
     */
    private $descripcion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Localizacion", type="string", length=100, nullable=true)
     */
    private $localizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=250, nullable=false)
     */
    private $imagen;

    /**
     * @var \Colorflor
     *
     * @ORM\ManyToOne(targetEntity="Colorflor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ColorFlor_idColorFlor", referencedColumnName="idColorFlor")
     * })
     */
    private $colorflorIdcolorflor;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Parteutil", inversedBy="plantaIdplanta")
     * @ORM\JoinTable(name="planta_has_parteutil",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Planta_idPlanta", referencedColumnName="idPlanta")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ParteUtil_idParteUtil", referencedColumnName="idParteUtil")
     *   }
     * )
     */
    private $parteutilIdparteutil;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usomedico", inversedBy="plantaIdplanta")
     * @ORM\JoinTable(name="planta_has_usomedico",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Planta_idPlanta", referencedColumnName="idPlanta")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="UsoMedico_idUsoMedico", referencedColumnName="idUsoMedico")
     *   }
     * )
     */
    private $usomedicoIdusomedico;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parteutilIdparteutil = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usomedicoIdusomedico = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdplanta(): ?int
    {
        return $this->idplanta;
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

    public function getLocalizacion(): ?string
    {
        return $this->localizacion;
    }

    public function setLocalizacion(?string $localizacion): self
    {
        $this->localizacion = $localizacion;

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

    public function getColorflorIdcolorflor(): ?Colorflor
    {
        return $this->colorflorIdcolorflor;
    }

    public function setColorflorIdcolorflor(?Colorflor $colorflorIdcolorflor): self
    {
        $this->colorflorIdcolorflor = $colorflorIdcolorflor;

        return $this;
    }

    /**
     * @return Collection|Parteutil[]
     */
    public function getParteutilIdparteutil(): Collection
    {
        return $this->parteutilIdparteutil;
    }

    public function addParteutilIdparteutil(Parteutil $parteutilIdparteutil): self
    {
        if (!$this->parteutilIdparteutil->contains($parteutilIdparteutil)) {
            $this->parteutilIdparteutil[] = $parteutilIdparteutil;
        }

        return $this;
    }

    public function removeParteutilIdparteutil(Parteutil $parteutilIdparteutil): self
    {
        if ($this->parteutilIdparteutil->contains($parteutilIdparteutil)) {
            $this->parteutilIdparteutil->removeElement($parteutilIdparteutil);
        }

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

}
