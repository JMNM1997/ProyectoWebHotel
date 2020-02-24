<?php

namespace App\Controller;

use App\Entity\Planta;
use App\Form\Planta1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface as PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/planta")
 */
class PlantaController extends AbstractController
{
    /**
     * @Route("/", name="planta_index", methods={"GET"})
     */
    public function index(): Response
    {
        $plantas = $this->getDoctrine()
            ->getRepository(Planta::class)
            ->findAll();

        return $this->render('planta/index.html.twig', [
            'plantas' => $plantas,
        ]);
    }

    /**
     * @Route("/listado", name="listado")
     */
    public function listado(PaginatorInterface $paginator, Request $request)
    {

        $plantas = $this->getDoctrine()
            ->getRepository(Planta::class)
            ->findAll();

        $listado = $paginator->paginate(
            $plantas, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return $this->render('planta/listado.html.twig', ["listado" => $listado]);
    }

    /**
     * @Route("/new", name="planta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $plantum = new Planta();
        $form = $this->createForm(Planta1Type::class, $plantum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheroimagen = $form['ficheroimagen']->getData();
            if ($ficheroimagen) {
                $nombrearchivo = $ficheroimagen->getClientOriginalName();
                $ficheroimagen->move(
                    $this->getParameter('directorio_imagenes'),
                    $nombrearchivo
                );
                $plantum->setImagen($nombrearchivo);
            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($plantum);
            $em->flush();
            return $this->redirectToRoute('planta_index');
        }


        return $this->render('planta/new.html.twig', [
            'plantum' => $plantum,
            'form' => $form->createView(),

        ]);
    }




    /**
     * @Route("/{idplanta}", name="planta_show", methods={"GET"})
     */
    public function show(Planta $plantum): Response
    {
        return $this->render('planta/show.html.twig', [
            'plantum' => $plantum,
        ]);
    }



    /**
     * @Route("/{idplanta}/edit", name="planta_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN", message="Necesitas ser el administrador")
     * @param integer $id
     * @return void
     */

    public function edit(Request $request, Planta $plantum): Response
    {
        $form = $this->createForm(Planta1Type::class, $plantum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ficheroimagen = $form['ficheroimagen']->getData();
            if ($ficheroimagen) {
                $nombrearchivo = $ficheroimagen->getClientOriginalName();
                $ficheroimagen->move(
                    $this->getParameter('directorio_imagenes'),
                    $nombrearchivo
                );
                $plantum->setImagen($nombrearchivo);
            }


            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('planta_index');
        }

        return $this->render('planta/edit.html.twig', [
            'plantum' => $plantum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idplanta}", name="planta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Planta $plantum): Response
    {
        if ($this->isCsrfTokenValid('delete' . $plantum->getIdplanta(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plantum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('planta_index');
    }
}
