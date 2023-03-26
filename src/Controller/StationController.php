<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Station;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Problem;
use App\Repository\ProblemRepository;
use App\Repository\LiftRepository;
use App\Entity\Lift;


class StationController extends AbstractController
{
    #[Route('/station/{id}', name: 'app_station_show')]
    public function show(Request $request, Station $station, EntityManagerInterface $entityManager, ProblemRepository $problems, LiftRepository $liftRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Enregistrer les données dans la base de données
            $problem = new Problem();
            $problem->setStation($station);
            $problem->setTitle($data['title']);
            $problem->setDescription($data['description']);
            $problem->setDate(new \DateTime('now'));


            $entityManager->persist($problem);
            $entityManager->flush();

            return $this->redirectToRoute('app_station_show', ['id' => $station->getId()]);
        }

        $problems = $problems->findBy(
            ['station' => $station],  // Critères de recherche
            ['date' => 'DESC'],       // Tri par date décroissante
            3                        // Limite de 3 résultats
        );
        $lifts = $liftRepository->findBy(['station' => $station]);
        return $this->render('station/show.html.twig', [
            'station' => $station,
            'form' => $form->createView(),
            'problems' => $problems,
            'lifts' => $lifts
        ]);


    }
}