<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SlopeController extends AbstractController
{
    #[Route('/slope', name: 'app_slope')]
    public function index(): Response
    {
        return $this->render('slope/index.html.twig', [
            'controller_name' => 'SlopeController',
        ]);
    }
}
