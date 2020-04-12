<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Reserva
 *
 * @ORM\Table(name="reserva", indexes={@ORM\Index(name="fk_Reserva_Cliente1_idx", columns={"Cliente_codCliente"}), @ORM\Index(name="fk_Reserva_Habitacion1_idx", columns={"Habitacion_codHabitacion"})})
 * @ORM\Entity
 */
class Reserva
{
    /**
     * @var int
     *
     * @ORM\Column(name="codReserva", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codreserva;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_entrada", type="date", nullable=false)
     */
    private $fechaEntrada;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_salida", type="date", nullable=false)
     */
    private $fechaSalida;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Cliente_codCliente", referencedColumnName="codCliente")
     * })
     */
    private $clienteCodcliente;

    /**
     * @var \Habitacion
     *
     * @ORM\ManyToOne(targetEntity="Habitacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Habitacion_codHabitacion", referencedColumnName="codHabitacion")
     * })
     */
    private $habitacionCodhabitacion;

    public function getCodreserva(): ?int
    {
        return $this->codreserva;
    }

    public function getFechaEntrada(): ?\DateTimeInterface
    {
        return $this->fechaEntrada;
    }

    public function setFechaEntrada(\DateTimeInterface $fechaEntrada): self
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    public function getFechaSalida(): ?\DateTimeInterface
    {
        return $this->fechaSalida;
    }

    public function setFechaSalida(\DateTimeInterface $fechaSalida): self
    {
        $this->fechaSalida = $fechaSalida;

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

    public function getHabitacionCodhabitacion(): ?Habitacion
    {
        return $this->habitacionCodhabitacion;
    }

    public function setHabitacionCodhabitacion(?Habitacion $habitacionCodhabitacion): self
    {
        $this->habitacionCodhabitacion = $habitacionCodhabitacion;

        return $this;
    }
}
