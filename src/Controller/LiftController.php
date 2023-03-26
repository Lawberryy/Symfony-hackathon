<?php

namespace App\Controller;

use App\Entity\Lift;
use App\Form\LiftType;
use App\Repository\LiftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lift')]
class LiftController extends AbstractController
{
    #[Route('/', name: 'app_lift_index', methods: ['GET'])]
    public function index(LiftRepository $liftRepository): Response
    {
        return $this->render('lift/index.html.twig', [
            'lifts' => $liftRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lift_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LiftRepository $liftRepository): Response
    {
        $lift = new Lift();
        $form = $this->createForm(LiftType::class, $lift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $liftRepository->save($lift, true);

            return $this->redirectToRoute('app_lift_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lift/new.html.twig', [
            'lift' => $lift,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lift_show', methods: ['GET'])]
    public function show(Lift $lift): Response
    {
        return $this->render('lift/show.html.twig', [
            'lift' => $lift,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lift_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lift $lift, LiftRepository $liftRepository): Response
    {
        $form = $this->createForm(LiftType::class, $lift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $liftRepository->save($lift, true);

            return $this->redirectToRoute('app_lift_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lift/edit.html.twig', [
            'lift' => $lift,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lift_delete', methods: ['POST'])]
    public function delete(Request $request, Lift $lift, LiftRepository $liftRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lift->getId(), $request->request->get('_token'))) {
            $liftRepository->remove($lift, true);
        }

        return $this->redirectToRoute('app_lift_index', [], Response::HTTP_SEE_OTHER);
    }
}
