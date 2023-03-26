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


    #[Route('/{id}', name: 'app_slope_show', methods: ['GET'])]
    public function show(Slope $slope): Response
    {
        return $this->render('slope/show.html.twig', [
            'slope' => $slope,
        ]);
    }



}
