<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comentario
 *
 * @ORM\Table(name="comentario", indexes={@ORM\Index(name="fk_Comentario_Cliente1_idx", columns={"Cliente_codCliente"})})
 * @ORM\Entity
 */
class Comentario
{
    /**
     * @var int
     *
     * @ORM\Column(name="idComentario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcomentario;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255, nullable=false)
     */
    private $texto;

    /**
     * @var int
     *
     * @ORM\Column(name="valoracion", type="integer", nullable=false)
     */
    private $valoracion;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Cliente_codCliente", referencedColumnName="codCliente")
     * })
     */
    private $clienteCodcliente;



    public function getIdcomentario(): ?int
    {
        return $this->idcomentario;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getValoracion(): ?int
    {
        return $this->valoracion;
    }

    public function setValoracion(int $valoracion): self
    {
        $this->valoracion = $valoracion;

        return $this;
    }

    public function getClienteCodcliente(): ?Cliente
    {
        return $this->clienteCodcliente;
    }

    public function setClienteCodcliente(?Cliente $clienteCodcliente): self
    {
        $this->clienteCodcliente = $clienteCodcliente;

        return $this;
    }
}
