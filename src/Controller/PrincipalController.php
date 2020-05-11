<?php

namespace App\Controller;

use App\Entity\Habitacion;
use App\Entity\Reserva;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservaRepository;
use App\Repository\HabitacionRepository;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;
use App\Controller\DateTime;
use App\Entity\Complemento;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PrincipalController extends AbstractController
{

    /**
     * @Route("/", name="inicio")
     */
    public function inicio(SessionInterface $session, PaginatorInterface $paginator, Request $request, ReservaRepository $reservaRepository, HabitacionRepository $habitacionRepository): Response
    {
        //lo primero es setear a null la sesión para poder aplicar unos filtros nuevos desde 0.
        $session->set('habitacionesListas', null);
        $em = $this->getDoctrine()->getManager();
        //complementos que se van a cargar en el checkbox
        $complementos = $em->getRepository(Complemento::class)->findAll();

        // filtrado por fechas, primer paso de búsqueda
        if (isset($_GET['fechaEntrada']) && (isset($_GET['fechaSalida']))) {

            //Vamos a pasar los valores a tipo fecha para poder mandarselos luego al método.
            $fechaEntrada = new \DateTime($_GET['fechaEntrada']);
            //¿Es fecha Entrada menos que el dia de hoy?
            $fechaActual = new \DateTime('now');

            if ($fechaActual > $fechaEntrada) {
                $fechaEntrada = $fechaActual;
            }

            $fechaSalida = new \DateTime($_GET['fechaSalida']);

            //¿Es fecha Salida menos que el dia de hoy? La pondremos 1 día mayor minimo ya que no tiene sentido que sea el mismo dia que la reserva.
            $fechaActualMas1 = new \DateTime('now');
            $fechaActualMas1->modify('+1 day');

            if ($fechaActualMas1 > $fechaSalida) {
                $fechaSalida = $fechaActualMas1;
            }

            //fecha entrada y salida. 
            //Devuelve la lista de habitaciones disponibles a mostrar, filtrando las que no pueden mostrarse por estar ocupadas.
            //en esas determinadas fechas.

            $habitacionesDisponibles = $reservaRepository->getHabitacionesDisponibles($fechaEntrada, $fechaSalida);
            //resultados paginados
            $listado = $paginator->paginate(
                $habitacionesDisponibles, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );


            //habitaciones lsitas para ser pasadas por sesión, por si el usuario quiere aplicarles un filtro
            $habitacionesListas = $session->get('habitacionesListas');
            /* si el carrito no existe se crea como un array vacío*/
            if (is_null($habitacionesListas)) {
                $habitacionesListas = array();
                $session->set('habitacionesListas', $habitacionesDisponibles);
            }

            return $this->render('principal/index.html.twig', ["listado" => $listado, "complementos" => $complementos]);
        }

        $emptyArray = [];

        $listado = $paginator->paginate(
            $emptyArray, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );


        return $this->render('principal/index.html.twig', ["listado" => $listado, "complementos" => $complementos]);
    }

    /**
     * @Route("/habitacion_detalle/{codhabitacion}", name="habitacion_detalle", methods={"GET"})
     * Parámetro id para ver por qué habitación se filtra
     */
    public function habitacion_detalle($codhabitacion): Response
    {

        $habitacion = $this->getDoctrine()->getRepository(Habitacion::class)->find((int) $codhabitacion);

        $habitacion = [
            "id" => $habitacion->getCodhabitacion(),
            "tipo" => $habitacion->getTipoIdtipo(),
            "planta" => $habitacion->getPlanta(),
            "imagen" => $habitacion->getImagen(),
            "complementoIdcomplemento" => $habitacion->getComplementoIdcomplemento(),
            "precio" => $habitacion->getPrecio(),
        ];


        return $this->render('habitacion/habitacion_detalle.html.twig', [
            'habitacion' => $habitacion,
        ]);
    }


    /**
     * @Route("/habitacionFiltro", name="habitacionFiltro", methods={"POST"})
     * 
     */
    public function habitacionFiltro(SessionInterface $session, PaginatorInterface $paginator, Request $request, HabitacionRepository $habitacionRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $complementos = $em->getRepository(Complemento::class)->findAll();

        //comprobamos las habitaciones que nos hemos traido de filtrar por fecha.
        //si no hay ninguna dará error pues hay que hacer una primera criba para ya filtrar las siguientes.

        $habitacionesListas = $session->get('habitacionesListas');
        if (is_null($habitacionesListas)) {

            return $this->render('principal/filtroerror.html.twig');
        }

        //si no seleccionas ningún complemento quiere decir que ignoras ese filtro y te da igual el número de complementos.
        //cuando el usuario no selecciona ningún complemento
        if (isset($_POST['planta']) && (isset($_POST['precio']) && (!(isset($_POST['complemento']))))) {

            $planta = $_POST['planta'];
            $precio = $_POST['precio'];

            $habitacionesFiltroplanta = $habitacionRepository->getHabitacionesFiltrosSincomplemento($planta, $precio, true);
        }
        //ahora vamos a ir mirando variables para ver que resultado se devuelve (el parametro complemento es opcional)
        if (isset($_POST['planta']) && (isset($_POST['precio']) && (isset($_POST['complemento'])))) {

            $planta = $_POST['planta'];
            $complemento = $_POST['complemento'];
            $precio = $_POST['precio'];

            $habitacionesFiltroplanta = $habitacionRepository->getHabitacionesFiltros($planta, $complemento, $precio, true);
        }


        $resultado = array();
        //ahora tenemos array1 (habitaciones filtradas segun fecha) y array2
        //trozo de codigo que nos saca habitaciones que coincidan entre las filtradas por fecha y las filtradas por otros valores
        //para que una habitación sea mostrada al usuario tiene que estar dentro de la fecha que solicito

        foreach ($habitacionesListas as $hFecha) {
            foreach ($habitacionesFiltroplanta as $hFiltro => $e) {
                if (($hFecha->getCodhabitacion()) == ($e->getCodhabitacion())) {
                    $resultado[] = $hFecha;
                }
            }
        }


        $listado = $paginator->paginate(
            $resultado, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('principal/filtro.html.twig', ["listado" => $listado, "complementos" => $complementos]);
    }
}
