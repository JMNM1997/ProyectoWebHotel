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

class PrincipalController extends AbstractController
{

    /**
     * @Route("/", name="inicio")
     */
    public function inicio(PaginatorInterface $paginator, Request $request, ReservaRepository $reservaRepository, HabitacionRepository $habitacionRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $habitaciones = $em->getRepository(Habitacion::class)->findAll();
        //complementos que se van a cargar en el checkbox
        $complementos = $em->getRepository(Complemento::class)->findAll();



        if (isset($_GET['planta']) && (isset($_GET['nombre']) && (isset($_GET['precio'])))) {
            $planta = $_GET['planta'];
            //string que envias al metodo de filtro
            $complemento = $_GET['nombre'];
            $precio = $_GET['precio'];

            $habitacionesDisponibles = $habitacionRepository->getHabitacionesFiltros($planta, $complemento, $precio, true);
            $listado = $paginator->paginate(
                $habitacionesDisponibles, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );

            return $this->render('principal/index.html.twig', ["listado" => $listado, "complemento" => $complementos]);
        }








        if (isset($_GET['planta']) && (isset($_GET['nombre']))) {
            $planta = $_GET['planta'];
            //string que envias al metodo de filtro
            $complemento = $_GET['nombre'];
            //$precio = $_GET['precio'];

            $habitacionesDisponibles = $habitacionRepository->getHabitacionesFiltrosSinprecio($complemento, $planta, true);
            $listado = $paginator->paginate(
                $habitacionesDisponibles, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );

            return $this->render('principal/index.html.twig', ["listado" => $listado, "complemento" => $complementos]);
        }
        // filtrado por fechas, primer paso de búsqueda
        if (isset($_GET['fechaEntrada']) && (isset($_GET['fechaSalida']))) {

            //Vamos a pasar los valores a tipo fecha para poder mandarselos luego al método.
            $fechaEntrada = new \DateTime($_GET['fechaEntrada']);
            $fechaSalida = new \DateTime($_GET['fechaSalida']);

            //fecha entrada y salida. 
            //Devuelve la lista de habitaciones disponibles a mostrar, filtrando las que no pueden mostrarse por estar ocupadas
            //en esas determinadas fechas.

            $habitacionesDisponibles = $reservaRepository->getHabitacionesDisponibles($fechaEntrada, $fechaSalida);
            //resultados paginados
            $listado = $paginator->paginate(
                $habitacionesDisponibles, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );

            return $this->render('principal/index.html.twig', ["listado" => $listado, "complemento" => $complemento]);
        }


        //precio máximo a pagar por el usuario

        if ((isset($_GET['precio']))) {


            $precio = $_GET['precio'];
            $habitacionesFiltroprecio = $habitacionRepository->getHabitacionesFiltroprecio($precio);

            $listado = $paginator->paginate(
                $habitacionesFiltroprecio, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );

            return $this->render('principal/index.html.twig', ["listado" => $listado, "complemento" => $complemento]);
        }




        //precio y planta

        if ((isset($_GET['precio']) && (isset($_GET['planta'])))) {


            $precio = $_GET['precio'];
            $habitacionesFiltroprecio = $habitacionRepository->getHabitacionesFiltroprecio($precio);

            $listado = $paginator->paginate(
                $habitacionesFiltroprecio, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );

            return $this->render('principal/index.html.twig', ["listado" => $listado, "complemento" => $complemento]);
        }

        //planta en la que se encuentra la habitación

        if ((isset($_GET['planta']))) {


            $planta = $_GET['planta'];
            $habitacionesFiltroplanta = $habitacionRepository->getHabitacionesFiltroplanta($planta);

            $listado = $paginator->paginate(
                $habitacionesFiltroplanta, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );

            return $this->render('principal/index.html.twig', ["listado" => $listado, "complemento" => $complemento]);
        }

        $emptyArray = [];

        $listado = $paginator->paginate(
            $emptyArray, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );


        return $this->render('principal/index.html.twig', ["listado" => $listado, "complemento" => $complementos]);
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
    public function habitacionFiltro(PaginatorInterface $paginator, Request $request, HabitacionRepository $habitacionRepository): Response
    {
        $planta = $_POST['planta'];
        $complemento = $_POST['complemento'];
        $precio = $_POST['precio'];

        $habitacionesFiltroplanta = $habitacionRepository->getHabitacionesFiltros($planta, $complemento, $precio, true);
        $em = $this->getDoctrine()->getManager();
        $complementos = $em->getRepository(Complemento::class)->findAll();

        $listado = $paginator->paginate(
            $habitacionesFiltroplanta, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('principal/filtro.html.twig', ["listado" => $listado, "complementos" => $complementos]);
    }
}
