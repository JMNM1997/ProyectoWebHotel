<?php

namespace App\Controller;

use App\Entity\Complemento;
use App\Form\ComplementoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/complemento")
 */
class ComplementoController extends AbstractController
{
    /**
     * @Route("/", name="complemento_index", methods={"GET"})
     */
    public function index(): Response
    {
        $complementos = $this->getDoctrine()
            ->getRepository(Complemento::class)
            ->findAll();

        return $this->render('complemento/index.html.twig', [
            'complementos' => $complementos,
        ]);
    }

    /**
     * @Route("/new", name="complemento_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $complemento = new Complemento();
        $form = $this->createForm(ComplementoType::class, $complemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($complemento);
            $entityManager->flush();

            return $this->redirectToRoute('complemento_index');
        }

        return $this->render('complemento/new.html.twig', [
            'complemento' => $complemento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcomplemento}", name="complemento_show", methods={"GET"})
     */
    public function show(Complemento $complemento): Response
    {
        return $this->render('complemento/show.html.twig', [
            'complemento' => $complemento,
        ]);
    }

    /**
     * @Route("/{idcomplemento}/edit", name="complemento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Complemento $complemento): Response
    {
        $form = $this->createForm(ComplementoType::class, $complemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('complemento_index');
        }

        return $this->render('complemento/edit.html.twig', [
            'complemento' => $complemento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcomplemento}", name="complemento_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Complemento $complemento): Response
    {
        if ($this->isCsrfTokenValid('delete'.$complemento->getIdcomplemento(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($complemento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('complemento_index');
    }
}
