<?php

namespace App\Controller;

use App\Entity\LinkTrail;
use App\Entity\Trail;
use App\Controller\SecurityController;
use App\Repository\LinkTrailRepository;
use App\Repository\TrailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyTrailsController extends AbstractController
{
    private $security;
    public function __construct(SecurityController $security)
    {
        $this->security = $security;
    }


    #[Route('/my/trails', name: 'app_my_trails')]
    public function index(
        TrailRepository $TrailRepository,
        LinkTrailRepository $LinkTrail,
    ): Response
    {
        $user = $this->security->getUser();

        // Get all my trails
        $allMyTrails = $TrailRepository->findBy(['owner' => $user->getId(), 'is_completed' => true]);

        $allStartTrails = [];
        foreach($allMyTrails as $trail){
            $startTrail = $LinkTrail->findBy(['trail_id' => $trail->getId()],['id' => 'ASC'],3);
            $allStartTrails[] = $startTrail;
        }

        // dd($allStartTrails);

        return $this->render('my_trails/index.html.twig', [
            'controller_name' => 'MyTrailsController',
            'Trails' => $allMyTrails,
            'trailsStart' => $allStartTrails
        ]);
    }
}
