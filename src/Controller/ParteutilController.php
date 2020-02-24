<?php

namespace App\Controller;

use App\Entity\Parteutil;
use App\Form\ParteutilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parteutil")
 */
class ParteutilController extends AbstractController
{
    /**
     * @Route("/", name="parteutil_index", methods={"GET"})
     */
    public function index(): Response
    {
        $parteutils = $this->getDoctrine()
            ->getRepository(Parteutil::class)
            ->findAll();

        return $this->render('parteutil/index.html.twig', [
            'parteutils' => $parteutils,
        ]);
    }

    /**
     * @Route("/new", name="parteutil_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $parteutil = new Parteutil();
        $form = $this->createForm(ParteutilType::class, $parteutil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parteutil);
            $entityManager->flush();

            return $this->redirectToRoute('parteutil_index');
        }

        return $this->render('parteutil/new.html.twig', [
            'parteutil' => $parteutil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idparteutil}", name="parteutil_show", methods={"GET"})
     */
    public function show(Parteutil $parteutil): Response
    {
        return $this->render('parteutil/show.html.twig', [
            'parteutil' => $parteutil,
        ]);
    }

    /**
     * @Route("/{idparteutil}/edit", name="parteutil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Parteutil $parteutil): Response
    {
        $form = $this->createForm(ParteutilType::class, $parteutil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parteutil_index');
        }

        return $this->render('parteutil/edit.html.twig', [
            'parteutil' => $parteutil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idparteutil}", name="parteutil_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Parteutil $parteutil): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parteutil->getIdparteutil(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($parteutil);
            $entityManager->flush();
        }

        return $this->redirectToRoute('parteutil_index');
    }
}
