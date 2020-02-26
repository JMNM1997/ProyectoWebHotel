<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Producto;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;



use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CarritoController extends AbstractController
{
    /**
     * 
     *
     * @Route ("/carrito", name="carrito")
     * 
     */
    public function mostrarCarrito(SessionInterface $session)
    {

        /* `para cada elemento del carrito se consulta la base de datos y se recuepran sus datos*/
        $productos = [];
        $carrito = $session->get('carrito');
        $Preciototal = 0;
        /* si el carrito no existe se crea como un array vacío*/
        if (is_null($carrito)) {
            $carrito = array();
            $session->set('carrito', $carrito);
        }
        /* se crea array con todos los datos de los productos y  la cantidad*/
        foreach ($carrito as $codigo => $cantidad) {
            $producto = $this->getDoctrine()->getRepository(Producto::class)->find((int) $codigo);
            $elem = [];
            $elem['idproducto'] = $producto->getIdproducto();
            $elem['nombre'] = $producto->getNombre();
            $elem['descripcion'] = $producto->getDescripcion();
            $elem['precio'] = $producto->getPrecio();
            $elem['imagen'] = $producto->getImagen();
            $elem['unidades'] = implode($cantidad);
            $elem['total'] = $producto->getPrecio() * implode($cantidad);

            $productos[] = $elem;

            $Preciototal += $elem['precio'] * $elem['unidades'];
        }
        return $this->render("carrito/carrito.html.twig", array('productos' => $productos, "Preciototal" => $Preciototal));
    }
    /**
     * 
     *
     * @Route ("/anadir", name="anadir")
     * 
     */
    public function anadir(SessionInterface $session)
    {

        $id = $_POST['idproducto'];

        $unidades = $_POST['unidades'];

        $carrito = $session->get('carrito');
        if (is_null($carrito)) {
            $carrito = array();
        }

        if (isset($carrito[$id])) {
            $carrito[$id]['unidades'] += intval($unidades);
        } else {
            $carrito[$id]['unidades'] = intval($unidades);
        }

        $session->set('carrito', $carrito);
        return $this->redirectToRoute('tienda');
    }


    /**
     * @Route("/eliminar", name="eliminar")
     */
    public function eliminar(SessionInterface $session)
    {

        $id = $_POST['idproducto'];
        $unidades = $_POST['unidades'];

        $carrito = $session->get('carrito');
        if (is_null($carrito)) {
            $carrito = array();
        }
        /*si existe el código sumamos las unidades, con mínimo de 0*/
        if (isset($carrito[$id])) {
            $carrito[$id]['unidades'] -= intval($unidades);
            if ($carrito[$id]['unidades'] <= 0) {
                unset($carrito[$id]);
            }
        }
        $session->set('carrito', $carrito);
        return $this->redirectToRoute('carrito');
    }
    /**
     * 
     *
     * @Route ("/correo", name="correo")
     * 
     */
    public function correo(SessionInterface $session)
    {

        /* `para cada elemento del carrito se consulta la base de datos y se recuepran sus datos*/
        $productos = [];
        $carrito = $session->get('carrito');
        $Preciototal = 0;
        /* si el carrito no existe se crea como un array vacío*/
        if (is_null($carrito)) {
            $carrito = array();
            $session->set('carrito', $carrito);
        }
        /* se crea array con todos los datos de los productos y  la cantidad*/
        foreach ($carrito as $codigo => $cantidad) {
            $producto = $this->getDoctrine()->getRepository(Producto::class)->find((int) $codigo);
            $elem = [];
            $elem['idproducto'] = $producto->getIdproducto();
            $elem['nombre'] = $producto->getNombre();
            $elem['descripcion'] = $producto->getDescripcion();
            $elem['precio'] = $producto->getPrecio();
            $elem['imagen'] = $producto->getImagen();
            $elem['unidades'] = implode($cantidad);
            $elem['total'] = $producto->getPrecio() * implode($cantidad);

            $productos[] = $elem;

            $Preciototal += $elem['precio'] * $elem['unidades'];
        }
        return $this->render("carrito/correo.html.twig", array('productos' => $productos, "Preciototal" => $Preciototal));
    }

    /**
     * @Route("/realizarPedido", name="realizarPedido")
     */
    public function realizarPedido(SessionInterface $session, \Swift_Mailer $mailer)
    {

        $email = $_POST['email'];
        if (isset($_POST['email'])) {



            $productos = [];
            $carrito = $session->get('carrito');
            $Preciototal = 0;
            /* si el carrito no existe se crea como un array vacío*/
            if (is_null($carrito)) {
                $carrito = array();
                $session->set('carrito', $carrito);
            }
            /* se crea array con todos los datos de los productos y  la cantidad*/
            foreach ($carrito as $codigo => $cantidad) {
                $producto = $this->getDoctrine()->getRepository(Producto::class)->find((int) $codigo);
                $elem = [];
                $elem['idproducto'] = $producto->getIdproducto();
                $elem['nombre'] = $producto->getNombre();
                $elem['descripcion'] = $producto->getDescripcion();
                $elem['precio'] = $producto->getPrecio();
                $elem['imagen'] = $producto->getImagen();
                $elem['unidades'] = implode($cantidad);
                $elem['total'] = $producto->getPrecio() * implode($cantidad);

                $productos[] = $elem;

                $Preciototal += $elem['precio'] * $elem['unidades'];
            }






            // Create the Transport
            $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
                ->setUsername('josemiguelnavarretemartinezn2@gmail.com')
                ->setPassword('Jurojose11');

            $mailer = new Swift_Mailer($transport);
            $message = (new \Swift_Message())
                ->setFrom(['josemiguelnavarretemartinezn2@gmail.com' => 'JoseMiguel'])
                ->setTo([$email])
                ->setSubject("Pedido confirmado -- Plantas Medicinales")
                ->setBody(
                    $this->renderView(
                        'carrito/correo.html.twig',
                        array('productos' => $productos, "Preciototal" => $Preciototal)
                    ),
                    'text/html'
                );
            $mailer->send($message);

            return $this->render("carrito/correo.html.twig", array('productos' => $productos, "Preciototal" => $Preciototal));
        }
    }
}
