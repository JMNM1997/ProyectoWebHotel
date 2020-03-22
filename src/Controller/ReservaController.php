<?php

namespace App\Controller;

use App\Entity\Reserva;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservaController extends AbstractController
{
    /**
     * @Route("/reserva/crearReserva", name="crear_reserva")
     *
     * 
     * @return void
     */
    public function crearReserva()
    {
        //primero el usuario en la bd
        $reserva = new Reserva();

        $fechaEntrada = $_POST['fechaEntrada'];
        $fechaSalida = $_POST['fechaSalida'];
        $clienteCodcliente = $_POST['clienteCodcliente'];
        $habitacionCodhabitacion = $_POST['habitacionCodhabitacion'];

        $reserva->setFechaEntrada($fechaEntrada);
        $reserva->setFechaSalida($fechaSalida);
        $reserva->setClienteCodcliente($clienteCodcliente);
        $reserva->setHabitacionCodhabitacion($habitacionCodhabitacion);


        $em = $this->getDoctrine()->getManager();
        $em->persist($reserva);
        $em->flush();


        return $this->redirectToRoute("inicio");
    }
}
