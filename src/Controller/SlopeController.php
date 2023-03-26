<?php

namespace App\Controller;

use App\Entity\Slope;
use App\Form\SlopeType;
use App\Repository\SlopeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/slope')]
class SlopeController extends AbstractController
{
    #[Route('/', name: 'app_slope_index', methods: ['GET'])]
    public function index(SlopeRepository $slopeRepository): Response
    {
        return $this->render('slope/index.html.twig', [
            'slopes' => $slopeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_slope_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SlopeRepository $slopeRepository): Response
    {
        $slope = new Slope();
        $form = $this->createForm(SlopeType::class, $slope);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slopeRepository->save($slope, true);

            return $this->redirectToRoute('app_slope_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slope/new.html.twig', [
            'slope' => $slope,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_slope_show', methods: ['GET'])]
    public function show(Slope $slope): Response
    {
        return $this->render('slope/show.html.twig', [
            'slope' => $slope,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_slope_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slope $slope, SlopeRepository $slopeRepository): Response
    {
        $form = $this->createForm(SlopeType::class, $slope);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slopeRepository->save($slope, true);

            return $this->redirectToRoute('app_slope_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slope/edit.html.twig', [
            'slope' => $slope,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_slope_delete', methods: ['POST'])]
    public function delete(Request $request, Slope $slope, SlopeRepository $slopeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slope->getId(), $request->request->get('_token'))) {
            $slopeRepository->remove($slope, true);
        }

        return $this->redirectToRoute('app_slope_index', [], Response::HTTP_SEE_OTHER);
    }
}
