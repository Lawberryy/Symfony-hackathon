<?php

namespace App\Controller;

use App\Entity\Problem;
use App\Form\ProblemType;
use App\Repository\ProblemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/problem')]
class ProblemController extends AbstractController
{
    #[Route('/', name: 'app_problem_index', methods: ['GET'])]
    public function index(ProblemRepository $problemRepository): Response
    {
        return $this->render('problem/index.html.twig', [
            'problems' => $problemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_problem_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProblemRepository $problemRepository): Response
    {
        $problem = new Problem();
        $form = $this->createForm(ProblemType::class, $problem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $problemRepository->save($problem, true);

            return $this->redirectToRoute('app_problem_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('problem/new.html.twig', [
            'problem' => $problem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_problem_show', methods: ['GET'])]
    public function show(Problem $problem): Response
    {
        return $this->render('problem/show.html.twig', [
            'problem' => $problem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_problem_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Problem $problem, ProblemRepository $problemRepository): Response
    {
        $form = $this->createForm(ProblemType::class, $problem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $problemRepository->save($problem, true);

            return $this->redirectToRoute('app_problem_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('problem/edit.html.twig', [
            'problem' => $problem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_problem_delete', methods: ['POST'])]
    public function delete(Request $request, Problem $problem, ProblemRepository $problemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$problem->getId(), $request->request->get('_token'))) {
            $problemRepository->remove($problem, true);
        }
        

        return $this->redirectToRoute('app_problem_index', [], Response::HTTP_SEE_OTHER);
    }
}
