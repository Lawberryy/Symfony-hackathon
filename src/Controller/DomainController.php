<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DomainRepository;
use App\Repository\StationRepository;
use Symfony\Component\Routing\Annotation\Route;

class DomainController extends AbstractController
{
    // ...

    #[Route('/domain/{id}', name: 'app_domain_show')]
    public function show(int $id, DomainRepository $domainRepository, StationRepository $StationRepository): Response
    {
      
        $domain = $domainRepository->find($id);

        if (!$domain) {
            throw $this->createNotFoundException('Le domaine avec l\'ID ' . $id . ' n\'existe pas');
        }
        $stations = $domain->getStations();

        return $this->render('domain/show.html.twig', [
            'domain' => $domain,
            'stations' => $stations
        ]);
    }
}