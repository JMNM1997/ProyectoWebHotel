<?php

namespace App\Controller;

use App\Entity\Noticia;
use App\Entity\Categoria;
use App\Form\NoticiaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;
use App\Repository\ActualidadRepository;

class ActualidadController extends AbstractController
{
    /**
     * @Route("/actualidad/", name="actualidad", methods={"GET"})
     */
    public function actualidad(PaginatorInterface $paginator, Request $request, ActualidadRepository $actualidadRepository): Response
    {
        $noticias = $this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findAll();

        $categorias = $this->getDoctrine()
            ->getRepository(Categoria::class)
            ->findAll();

        // buscamos las palabras de un titular
        if (isset($_GET['buscar'])) {

            $palabra = $_GET['buscar'];

            $filtroNoticias = $actualidadRepository->buscador($palabra);
            //resultados paginados
            $listado = $paginator->paginate(
                $filtroNoticias, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );
        }


        //sacar ultimas 4 noticias
        $ultimasNoticias = $actualidadRepository->getUltimasNoticias();

        $listado = $paginator->paginate(
            $noticias, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('actualidad/noticias.html.twig', [
            'listado' => $listado, 'categorias' => $categorias, 'ultimasNoticias' => $ultimasNoticias,
        ]);
    }

    /**
     * @Route("/noticia_detalle/{codnoticia}", name="noticia_detalle", methods={"GET"})
     * Parámetro id para ver por qué noticia se filtra
     */
    public function noticia_detalle($codnoticia): Response
    {

        $noticia = $this->getDoctrine()->getRepository(Noticia::class)->find((int) $codnoticia);


        $noticia = [
            "codnoticia" => $noticia->getCodnoticia(),
            "titular" => $noticia->getTitular(),
            "descripcion" => $noticia->getDescripcion(),
            "imagen" => $noticia->getImagen(),
            "fecha" => $noticia->getFecha(),
            "categoriaIdcategoria" => $noticia->getCategoriaIdcategoria(),
        ];


        return $this->render('actualidad/noticia_detalle.html.twig', [
            'noticia' => $noticia,
        ]);
    }
    /**
     * @Route("/actualidad_categoria/{idcategoria}", name="actualidad_categoria", methods={"GET"})
     * Parámetro id de la categoria por la que se filtra
     */
    public function actualidad_categoria($idcategoria, PaginatorInterface $paginator, Request $request, ActualidadRepository $actualidadRepository): Response
    {


        $noticias_categoria = $actualidadRepository->filtrocategoria($idcategoria);

        //cargamos todas las categorias
        $categorias = $this->getDoctrine()
            ->getRepository(Categoria::class)
            ->findAll();
        // buscamos las palabras de un titular
        if (isset($_GET['buscar'])) {

            $palabra = $_GET['buscar'];

            $filtroNoticias = $actualidadRepository->buscador($palabra);
            //resultados paginados
            $listado = $paginator->paginate(
                $filtroNoticias, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );
        }


        //sacar ultimas 4 noticias
        $ultimasNoticias = $actualidadRepository->getUltimasNoticias();

        //resultados paginados
        $listado = $paginator->paginate(
            $noticias_categoria, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('actualidad/noticias.html.twig', [
            'listado' => $listado, 'categorias' => $categorias, 'ultimasNoticias' => $ultimasNoticias,
        ]);
    }
}
