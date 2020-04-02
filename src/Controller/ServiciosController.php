<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;


class ServiciosController extends AbstractController
{

    /**
     * @Route("/servicios", name="servicios_index")
     */
    public function inicio(): Response
    {


        return $this->render('servicios/servicios.html.twig');
    }
}
