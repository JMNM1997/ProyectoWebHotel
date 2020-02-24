<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Producto;

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

            $productos[] = $elem;
        }
        return $this->render("carrito/carrito.html.twig", array('productos' => $productos));
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
        return $this->redirectToRoute('carrito');
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
     * @Route("/realizarPedido", name="realizarPedido")
     */
    public function realizarPedido(SessionInterface $session)
    {
    }
}
