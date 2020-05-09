<?php

namespace App\Controller;

use App\Entity\Noticia;
use App\Form\NoticiaType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/noticia")
 */
class NoticiaController extends AbstractController
{
    /**
     * @Route("/", name="noticia_index", methods={"GET"})
     */
    public function index(): Response
    {
        $noticias = $this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findAll();

        return $this->render('noticia/index.html.twig', [
            'noticias' => $noticias,
        ]);
    }

    /**
     * @Route("/new", name="noticia_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $noticium = new Noticia();
        $form = $this->createForm(NoticiaType::class, $noticium);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $imagen = $form['imagen']->getData();
            if ($imagen) {
                $nombrearchivo = $imagen->getClientOriginalName();
                $imagen->move(
                    $this->getParameter('directorio_imagenes'),
                    $nombrearchivo
                );
                $noticium->setImagen($nombrearchivo);
            }
            $fecha = $form['fecha']->getData();
            //si la fecha es menor a la actual devolveremos al usuario la fecha actual
            $fechaActual = new DateTime('now');
            if ($fechaActual > $fecha) {
                $noticium->setFecha($fechaActual);
            } else {
                $noticium->setFecha($fecha);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($noticium);
            $em->flush();
            return $this->redirectToRoute('noticia_index');
        }

        return $this->render('noticia/new.html.twig', [
            'noticium' => $noticium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{codnoticia}", name="noticia_show", methods={"GET"})
     */
    public function show(Noticia $noticium): Response
    {
        return $this->render('noticia/show.html.twig', [
            'noticium' => $noticium,
        ]);
    }

    /**
     * @Route("/{codnoticia}/edit", name="noticia_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Noticia $noticium): Response
    {
        $form = $this->createForm(NoticiaType::class, $noticium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagen = $form['imagen']->getData();
            if ($imagen) {
                $nombrearchivo = $imagen->getClientOriginalName();
                $imagen->move(
                    $this->getParameter('directorio_imagenes'),
                    $nombrearchivo
                );
                $noticium->setImagen($nombrearchivo);
            }
            $fecha = $form['fecha']->getData();
            $noticium->setFecha($fecha);


            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('noticia_index');
        }

        return $this->render('noticia/edit.html.twig', [
            'noticium' => $noticium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{codnoticia}", name="noticia_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Noticia $noticium): Response
    {
        if ($this->isCsrfTokenValid('delete' . $noticium->getCodnoticia(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($noticium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('noticia_index');
    }
}
