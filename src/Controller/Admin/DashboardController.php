<?php

namespace App\Controller\Admin;

use App\Entity\Problem;
use App\Entity\Station;
use App\Repository\ProblemRepository;
use App\Repository\LiftRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(StationCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SymfoHackaton');
    }

    public function configureMenuItems(): iterable
    {
        $problemRepository = $this->entityManager->getRepository(Problem::class);

        yield MenuItem::linkToCrud('Station', 'fas fa-list', Station::class);

        if ($problemRepository->count([]) > 0) {
            yield MenuItem::linkToCrud('Problèmes', 'fas fa-list', Problem::class);
        }
    }
}
