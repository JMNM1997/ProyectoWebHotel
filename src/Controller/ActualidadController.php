<?php

namespace App\Controller;

use App\Entity\Noticia;
use App\Entity\Categoria;
use App\Form\NoticiaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActualidadController extends AbstractController
{
    /**
     * @Route("/noticias/", name="actualidad", methods={"GET"})
     */
    public function noticias(): Response
    {
        $noticias = $this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findAll();

        $categorias = $this->getDoctrine()
            ->getRepository(Categoria::class)
            ->findAll();

        return $this->render('actualidad/noticias.html.twig', [
            'noticias' => $noticias, 'categorias' => $categorias,
        ]);
    }
}
