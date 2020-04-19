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
     * @Route("/noticias/", name="actualidad", methods={"GET"})
     */
    public function noticias(PaginatorInterface $paginator, Request $request, ActualidadRepository $actualidadRepository): Response
    {
        $noticias = $this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findAll();

        $categorias = $this->getDoctrine()
            ->getRepository(Categoria::class)
            ->findAll();

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
}
