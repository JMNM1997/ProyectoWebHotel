<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Cliente;
use App\Entity\Habitacion;
use App\Entity\Comentario;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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
     * @IsGranted("ROLE_USER")
     * @return void
     */
    public function crearReserva(\Swift_Mailer $mailer)
    {

        //primero el usuario en la bd
        $reserva = new Reserva();

        $id = $this->getUser()->getId();


        $userid = $this->getDoctrine()->getRepository(Cliente::class)->findOneBy(['user' => $id]);
        $email = $userid->getUser()->getEmail();
        $reserva->setClienteCodcliente($userid);
        $habitacionCodhabitacion = $_POST['habitacionCodhabitacion'];
        $habid = $this->getDoctrine()->getRepository(Habitacion::class)->findOneBy(['codhabitacion' => $habitacionCodhabitacion]);
        $reserva->setHabitacionCodhabitacion($habid);

        // fechas

        $fechaEntrada = $_POST['fechaEntrada'];
        $fechaSalida = $_POST['fechaSalida'];
        $fechaEntradaDATE = new \DateTime($fechaEntrada);

        //¿Es fecha Entrada menos que el dia de hoy?
        $fechaActual = new \DateTime('now');

        if ($fechaActual > $fechaEntradaDATE) {
            $fechaEntradaDATE = $fechaActual;
        }

        $fechaSalidaDATE = new \DateTime($fechaSalida);

        //¿Es fecha Salida menos que el dia de hoy? La pondremos 1 día mayor minimo ya que no tiene sentido que sea el mismo dia que la reserva.
        $fechaActualMas1 = new \DateTime('now');
        $fechaActualMas1->modify('+1 day');

        if ($fechaActualMas1 > $fechaSalidaDATE) {
            $fechaSalidaDATE = $fechaActualMas1;
        }


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


        return $this->redirectToRoute("reserva_index");
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
    /**
     * @Route ("/evaluar", name="evaluar")
     * 
     */
    public function evaluar()
    {
        $comentario = new Comentario();

        $id = $this->getUser()->getId();

        $userid = $this->getDoctrine()->getRepository(Cliente::class)->findOneBy(['user' => $id]);
        $comentario->setClienteCodcliente($userid);

        $review = $_POST['review'];

        $comentario->setTexto($review);

        $radioVal = $_POST["grp1"];

        if ($radioVal == "1") {
            $comentario->setValoracion(1);
        }
        if ($radioVal == "2") {
            $comentario->setValoracion(2);
        }
        if ($radioVal == "3") {
            $comentario->setValoracion(3);
        }
        if ($radioVal == "4") {
            $comentario->setValoracion(4);
        }
        if ($radioVal == "5") {
            $comentario->setValoracion(5);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($comentario);
        $em->flush();



        return $this->redirectToRoute('reserva_index');
    }
}
