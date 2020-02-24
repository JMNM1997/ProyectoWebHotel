<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Producto;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;
use App\Entity\Usomedico;
use App\Repository\ProductoRepository;

class TiendaController extends AbstractController
{
    /**
     * @Route("/tienda", name="tienda")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productos = $this->getDoctrine()
            ->getRepository(Producto::class)
            ->findAll();
        $usomedicos = $em->getRepository(Usomedico::class)->findAll();

        $listado = $paginator->paginate(
            $productos, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return $this->render('tienda/tienda.html.twig', ['usomedicos' => $usomedicos, "listado" => $listado]);
    }
    /**
     * @Route("/filtrosProductos/{uso}")
     *
     * @param [type] $uso
     * @return void
     */
    public function ProbarConsultas($uso, ProductoRepository $productoRepository)
    {
        $usomedicos = $this->getDoctrine()
            ->getRepository(Usomedico::class)
            ->findAll();

        $em = $this->getDoctrine()->getManager();
        $productoFiltro = $productoRepository->getProductosUso($uso);

        return $this->render('tienda/tienda.html.twig', [
            'productos' => $productoFiltro, 'usomedicos' => $usomedicos
        ]);
    }
}
