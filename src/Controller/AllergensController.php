<?php

namespace App\Controller;

use App\Entity\Allergens;
use App\Form\AllergensType;
use App\Repository\AllergensRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/allergens")
 */
class AllergensController extends AbstractController
{
    /**
     * @Route("/", name="allergens_index", methods={"GET"})
     */
    public function index(AllergensRepository $allergensRepository): Response
    {
        return $this->render('allergens/index.html.twig', [
            'allergens' => $allergensRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="allergens_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $allergen = new Allergens();
        $form = $this->createForm(AllergensType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($allergen);
            $entityManager->flush();

            return $this->redirectToRoute('allergens_index');
        }

        return $this->render('allergens/new.html.twig', [
            'allergen' => $allergen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="allergens_show", methods={"GET"})
     */
    public function show(Allergens $allergen): Response
    {
        return $this->render('allergens/show.html.twig', [
            'allergen' => $allergen,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="allergens_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Allergens $allergen): Response
    {
        $form = $this->createForm(AllergensType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('allergens_index', [
                'id' => $allergen->getId(),
            ]);
        }

        return $this->render('allergens/edit.html.twig', [
            'allergen' => $allergen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="allergens_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Allergens $allergen): Response
    {
        if ($this->isCsrfTokenValid('delete'.$allergen->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($allergen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('allergens_index');
    }
}
