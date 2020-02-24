<?php

namespace App\Controller;

use App\Entity\Colorflor;
use App\Form\ColorflorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/colorflor")
 */
class ColorflorController extends AbstractController
{
    /**
     * @Route("/", name="colorflor_index", methods={"GET"})
     */
    public function index(): Response
    {
        $colorflors = $this->getDoctrine()
            ->getRepository(Colorflor::class)
            ->findAll();

        return $this->render('colorflor/index.html.twig', [
            'colorflors' => $colorflors,
        ]);
    }

    /**
     * @Route("/new", name="colorflor_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $colorflor = new Colorflor();
        $form = $this->createForm(ColorflorType::class, $colorflor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($colorflor);
            $entityManager->flush();

            return $this->redirectToRoute('colorflor_index');
        }

        return $this->render('colorflor/new.html.twig', [
            'colorflor' => $colorflor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcolorflor}", name="colorflor_show", methods={"GET"})
     */
    public function show(Colorflor $colorflor): Response
    {
        return $this->render('colorflor/show.html.twig', [
            'colorflor' => $colorflor,
        ]);
    }

    /**
     * @Route("/{idcolorflor}/edit", name="colorflor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Colorflor $colorflor): Response
    {
        $form = $this->createForm(ColorflorType::class, $colorflor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('colorflor_index');
        }

        return $this->render('colorflor/edit.html.twig', [
            'colorflor' => $colorflor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcolorflor}", name="colorflor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Colorflor $colorflor): Response
    {
        if ($this->isCsrfTokenValid('delete' . $colorflor->getIdcolorflor(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($colorflor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('colorflor_index');
    }
}
