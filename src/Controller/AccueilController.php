<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StationRepository;
use App\Repository\LiftRepository;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(StationRepository $stationsRepository, Liftrepository $liftRepository): Response
    {
        $stations = $stationsRepository->findAll();
        $lifts = $liftRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'stations' => $stations,
            'lifts' => $lifts
        ]);
    }
}
