<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Cliente;
use App\Entity\Habitacion;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PropertyAccess\PropertyAccess;
use App\Controller\DateTime;


class ReservaController extends AbstractController
{

    /**
     * @Route("/reserva", name="reserva_index", methods={"GET"})
     */
    public function index(): Response
    {

        $id = $this->getUser()->getId();


        $userid = $this->getDoctrine()->getRepository(Cliente::class)->findOneBy(['user' => $id]);


        $reserva = $this->getDoctrine()
            ->getRepository(Reserva::class)
            ->findOneBy(['clienteCodcliente' => $userid]);

        if ($reserva == null) {
            return $this->render('reserva/reservaerror.html.twig');
        }


        return $this->render('reserva/mireserva.html.twig', [
            'reserva' => $reserva,
        ]);
    }
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

        $id = $this->getUser()->getId();


        $userid = $this->getDoctrine()->getRepository(Cliente::class)->findOneBy(['user' => $id]);
        $reserva->setClienteCodcliente($userid);
        $habitacionCodhabitacion = $_POST['habitacionCodhabitacion'];
        $habid = $this->getDoctrine()->getRepository(Habitacion::class)->findOneBy(['codhabitacion' => $habitacionCodhabitacion]);
        $reserva->setHabitacionCodhabitacion($habid);


        $fechaEntrada = $_POST['fechaEntrada'];
        $fechaSalida = $_POST['fechaSalida'];
        $fechaEntradaDATE = new \DateTime($fechaEntrada);
        $fechaSalidaDATE = new \DateTime($fechaSalida);



        $reserva->setFechaEntrada($fechaEntradaDATE);
        $reserva->setFechaSalida($fechaSalidaDATE);




        $em = $this->getDoctrine()->getManager();
        $em->persist($reserva);
        $em->flush();


        return $this->redirectToRoute("inicio");
    }
}
