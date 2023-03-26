<?php

namespace App\Controller\Admin;

use App\Entity\Lift;
use App\Entity\Slope;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Station;
use App\Entity\Domain;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(StationCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SymfoHackaton');
    }

    public function configureMenuItems(): iterable
    {
        if ($this->authChecker->isGranted('ROLE_SU')) {
            yield MenuItem::linkToCrud('Domain', ' fa fa-map-o', Domain::class)
                ->setPermission('ROLE_SU');
        }
<<<<<<< HEAD
        yield MenuItem::linkToCrud('Lift', 'fa fa-person-ski-lift', Lift::class);
        yield MenuItem::linkToCrud('Slope', 'fa fa-solid fa-angle', Slope::class);
=======
        yield MenuItem::linkToCrud('Station', 'fa fa-snowflake-o', Station::class);
>>>>>>> 2cd37fc1d29dc25175a5af3257ac1751e5b1c504
    }
}
