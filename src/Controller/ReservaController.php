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
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Spipu\Html2Pdf\Html2Pdf;


class ReservaController extends AbstractController
{

    /**
     * @Route("/reserva", name="reserva_index", methods={"GET"})
     */
    public function index(): Response
    {

        $id = $this->getUser()->getId();


        $userid = $this->getDoctrine()->getRepository(Cliente::class)->findBy(['user' => $id]);


        $reserva = $this->getDoctrine()
            ->getRepository(Reserva::class)
            ->findBy(['clienteCodcliente' => $userid]);

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
    public function crearReserva(\Swift_Mailer $mailer)
    {
        $email = 'josemiguelnavarretemartinez@gmail.com';
        //primero el usuario en la bd
        $reserva = new Reserva();

        $id = $this->getUser()->getId();


        $userid = $this->getDoctrine()->getRepository(Cliente::class)->findOneBy(['user' => $id]);
        $reserva->setClienteCodcliente($userid);
        $habitacionCodhabitacion = $_POST['habitacionCodhabitacion'];
        $habid = $this->getDoctrine()->getRepository(Habitacion::class)->findOneBy(['codhabitacion' => $habitacionCodhabitacion]);
        $reserva->setHabitacionCodhabitacion($habid);

        // fechas

        $fechaEntrada = $_POST['fechaEntrada'];
        $fechaSalida = $_POST['fechaSalida'];
        $fechaEntradaDATE = new \DateTime($fechaEntrada);
        $fechaSalidaDATE = new \DateTime($fechaSalida);

        //asignar fechas al objeto reserva

        $reserva->setFechaEntrada($fechaEntradaDATE);
        $reserva->setFechaSalida($fechaSalidaDATE);


        $em = $this->getDoctrine()->getManager();
        $em->persist($reserva);
        $em->flush();



        // Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername('josemiguelnavarretemartinezn2@gmail.com') //correo y contraseña reales
            ->setPassword('Jurojose11');

        $mailer = new Swift_Mailer($transport);
        $message = (new \Swift_Message()) //rellenar parametros
            ->setFrom(['josemiguelnavarretemartinezn2@gmail.com' => 'JoseMiguel'])
            ->setTo([$email])
            ->setSubject("Pedido confirmado -- Reserva MYHOTEL")
            ->setBody(
                $this->renderView(
                    'reserva/reservarealizada.html.twig',
                    array('reserva' => $reserva) //variables para la vista
                ),
                'text/html'
            );
        $mailer->send($message);






        return $this->redirectToRoute("inicio");
    }

    /**
     * @Route("/{codreserva}", name="borrarReserva", methods={"DELETE"})
     */
    public function borrarReserva(Request $request, Reserva $reserva): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reserva->getCodreserva(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reserva);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reserva_index');
    }
}
