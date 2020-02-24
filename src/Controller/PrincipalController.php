<?php

namespace App\Controller;

use App\Entity\Planta;
use App\Entity\Colorflor;
use App\Form\ColorflorType;
use App\Entity\User;
use App\Form\PlantaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Session\Session;
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

        $plantas = $em->getRepository(Planta::class)->findAll();
        $colorflors = $em->getRepository(Colorflor::class)->findAll();


        $listado = $paginator->paginate(
            $plantas, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return $this->render('principal/index.html.twig', [
            'listado' => $listado, 'colorflors' => $colorflors,
        ]);;
    }



    /**
     * @Route("/usuario/crear", name="crear_usuario")
     *
     * @param UserPasswordEncoderInterface $encoder
     * @return void
     */
    public function crearUsuario(UserPasswordEncoderInterface $encoder)
    {
        $usuario = new User();
        $contrasena = "1234";
        $encoded = $encoder->encodePassword($usuario, $contrasena);
        $usuario->setPassword($encoded);
        $usuario->setEmail("jm@es.es");
        $em = $this->getDoctrine()->getManager();
        $em->persist($usuario);
        $em->flush();

        return $this->redirectToRoute("principal");
    }



    /**
     * @Route("/planta_detalle/{idplanta}", name="planta_detalle", methods={"GET"})
     */
    public function planta_detalle($idplanta): Response
    {

        $planta = $this->getDoctrine()->getRepository(Planta::class)->find((int) $idplanta);

        $planta = [
            "id" => $planta->getIdplanta(),
            "nombre" => $planta->getNombre(),
            "descripcion" => $planta->getDescripcion(),
            "localizacion" => $planta->getLocalizacion(),
            "imagen" => $planta->getImagen(),
            "colorflorIdcolorflor" => $planta->getColorflorIdcolorflor(),
            "parteutilIdparteutil" => $planta->getParteutilIdparteutil(),
            "usomedicoIdusomedico" => $planta->getUsomedicoIdusomedico(),
        ];


        return $this->render('planta/planta_detalle.html.twig', [
            'planta' => $planta,
        ]);
    }


    /**
     * @Route("/filtros/{color}")
     *
     * @param [type] $color
     * @return void
     */
    public function ProbarConsultas(PaginatorInterface $paginator, Request $request, $color, PlantaRepository $plantaRepository)
    {
        $colorflors = $this->getDoctrine()
            ->getRepository(Colorflor::class)
            ->findAll();


        $em = $this->getDoctrine()->getManager();
        $plantaFiltro = $plantaRepository->getPlantasColorFlor($color);



        $listado = $paginator->paginate(
            $plantaFiltro, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return $this->render('principal/index.html.twig', [
            'listado' => $listado, 'colorflors' => $colorflors
        ]);
    }
}
