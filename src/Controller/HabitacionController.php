<?php

namespace App\Controller;

use App\Entity\Habitacion;
use App\Form\HabitacionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/habitacion")
 */
class HabitacionController extends AbstractController
{
    /**
     * @Route("/", name="habitacion_index", methods={"GET"})
     */
    public function index(): Response
    {
        $habitacions = $this->getDoctrine()
            ->getRepository(Habitacion::class)
            ->findAll();

        return $this->render('habitacion/index.html.twig', [
            'habitacions' => $habitacions,
        ]);
    }

    /**
     * @Route("/new", name="habitacion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $habitacion = new Habitacion();
        $form = $this->createForm(HabitacionType::class, $habitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagen = $form['imagen']->getData();
            if ($imagen) {
                $nombrearchivo = $imagen->getClientOriginalName();
                $imagen->move(
                    $this->getParameter('directorio_imagenes'),
                    $nombrearchivo
                );
                $habitacion->setImagen($nombrearchivo);
            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($habitacion);
            $em->flush();
            return $this->redirectToRoute('habitacion_index');
        }

        return $this->render('habitacion/new.html.twig', [
            'habitacion' => $habitacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{codhabitacion}", name="habitacion_show", methods={"GET"})
     */
    public function show(Habitacion $habitacion): Response
    {
        return $this->render('habitacion/show.html.twig', [
            'habitacion' => $habitacion,
        ]);
    }

    /**
     * @Route("/{codhabitacion}/edit", name="habitacion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Habitacion $habitacion): Response
    {
        $form = $this->createForm(HabitacionType::class, $habitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagen = $form['imagen']->getData();
            if ($imagen) {
                $nombrearchivo = $imagen->getClientOriginalName();
                $imagen->move(
                    $this->getParameter('directorio_imagenes'),
                    $nombrearchivo
                );
                $habitacion->setImagen($nombrearchivo);
            }

            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('habitacion_index');
        }

        return $this->render('habitacion/edit.html.twig', [
            'habitacion' => $habitacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{codhabitacion}", name="habitacion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Habitacion $habitacion): Response
    {
        if ($this->isCsrfTokenValid('delete' . $habitacion->getCodhabitacion(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($habitacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('habitacion_index');
    }
}
