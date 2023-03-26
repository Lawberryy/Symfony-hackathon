<?php

namespace App\Controller\Admin;

use App\Entity\Lift;
use App\Entity\Slope;
use App\Entity\Problem;
use App\Entity\Station;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Domain;

class DashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $entityManager;
    private AuthorizationCheckerInterface $authChecker;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authChecker)
    {
        $this->entityManager = $entityManager;
        $this->authChecker = $authChecker;
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
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-home', 'main');
        if ($this->authChecker->isGranted('ROLE_SU')) {
            yield MenuItem::section('Super User');
            yield MenuItem::linkToCrud('Domain', ' fa fa-map-o', Domain::class)
                ->setPermission('ROLE_SU');
        }
        yield MenuItem::section('Admin');
        yield MenuItem::linkToCrud('Station', 'fa fa-snowflake-o', Station::class);
        if ($this->authChecker->isGranted('ROLE_ADMIN') && $this->getUser()->getStations()->count() > 0 || $this->authChecker->isGranted('ROLE_SU')) {
            yield MenuItem::subMenu('More...')
                ->setSubItems([
                    MenuItem::linkToCrud('Slopes', 'fa fa-snowflake-o', Slope::class),
                    MenuItem::linkToCrud('Lifts', 'fa fa-snowflake-o', Lift::class),
                ]);

        }
        if ($problemRepository->count([]) > 0) {
            yield MenuItem::linkToCrud('Problèmes', 'fas fa-list', Problem::class);
        }
    }
}
