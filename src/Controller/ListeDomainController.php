<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DomainRepository;
use Symfony\Component\Routing\Annotation\Route;

class ListeDomainController extends AbstractController
{
    #[Route('/domain', name: 'app_liste_domain')]
    public function index(DomainRepository $domainRepository): Response
    {
        $domains = $domainRepository->findAll();
        return $this->render('liste_domain/index.html.twig', [
            'domains' => $domains, 
        ]);
    }
}
