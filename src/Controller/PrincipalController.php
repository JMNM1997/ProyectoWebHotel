<?php

namespace App\Controller;

use App\Entity\Habitacion;
use App\Entity\Reserva;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservaRepository;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;
use App\Controller\DateTime;


class PrincipalController extends AbstractController
{

    /**
     * @Route("/", name="inicio")
     */
    public function inicio(PaginatorInterface $paginator, Request $request, ReservaRepository $reservaRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $habitaciones = $em->getRepository(Habitacion::class)->findAll();

        // fechas
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

            return $this->render('principal/index.html.twig', ["listado" => $listado]);
        }


        $listado = $paginator->paginate(
            $habitaciones, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );


        return $this->render('principal/index.html.twig', ["listado" => $listado]);
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
            "extras" => $habitacion->getExtras(),
            "precio" => $habitacion->getPrecio(),
        ];


        return $this->render('habitacion/habitacion_detalle.html.twig', [
            'habitacion' => $habitacion,
        ]);
    }
}
