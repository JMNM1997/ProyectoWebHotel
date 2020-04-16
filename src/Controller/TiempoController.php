<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;


class TiempoController extends AbstractController
{

    /**
     * @Route("/tiempo", name="tiempo_index")
     */
    public function inicio(): Response
    {


        return $this->render('servicios/tiempo.html.twig');
    }
}
