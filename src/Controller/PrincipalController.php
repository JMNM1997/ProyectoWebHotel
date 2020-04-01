<?php

namespace App\Controller;

use App\Entity\Habitacion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlantaRepository;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;


class PrincipalController extends AbstractController
{

    /**
     * @Route("/", name="inicio")
     */
    public function inicio(PaginatorInterface $paginator, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $habitaciones = $em->getRepository(Habitacion::class)->findAll();
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
