<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;


class ContactoController extends AbstractController
{

    /**
     * @Route("/contacto", name="contacto_index")
     */
    public function inicio(): Response
    {


        return $this->render('servicios/contacto.html.twig');
    }

    /**
     * @Route("/contacto/send", name="enviar_email")
     */
    public function enviarEmail(\Swift_Mailer $mailer): Response
    {
        //no funciona ahora mismo
        //email de empresa irá aquuí
        $emailMio = 'josemiguelnavarretemartinez@gmail.com';
        //en construccion


        //variables de contacto
        $mensaje = $_POST['mensaje'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $asunto = $_POST['asunto'];




        // Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername('josemiguelnavarretemartinezn2@gmail.com') //correo y contraseña reales
            ->setPassword('Jurojose11');

        $mailer = new Swift_Mailer($transport);
        $message = (new \Swift_Message()) //rellenar parametros
            ->setFrom(['josemiguelnavarretemartinezn2@gmail.com' => 'JoseMiguel'])
            ->setTo([$emailMio])
            ->setSubject("Formulario de contacto - Hotel")
            ->setBody(
                $this->renderView(
                    'servicios/contactorealizado.html.twig',
                    array('mensaje' => $mensaje, 'nombre' => $nombre, 'email' => $email, 'asunto' => $asunto,) //variables para la vista
                ),
                'text/html'
            );
        $mailer->send($message);


        return $this->redirectToRoute("contacto_index");
    }
}
