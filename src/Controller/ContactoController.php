<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;


class ContactoController extends AbstractController
{

    /**
     * @Route("/contacto", name="contacto_index")
     */
    public function inicio(): Response
    {


        return $this->render('servicios/contacto.html.twig');
    }
}
