<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Orders;
use App\Entity\Product;
use App\Entity\Categories;
use App\Entity\AccueilSlider;
use App\Entity\Transporteurs;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/manajar", name="administrar")
     */
    public function index(): Response
    {
        return parent::index();
        //$routeBuilder = $this->get(AdminUrlGenerator::class);

        //return $this->redirect($routeBuilder->setController(ProductCrudController::class)->generateUrl());
        //return $this->render('dashboard/index.html.twig', []);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Naturea');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Produits', 'fas fa-shopping-cart', Product::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Categories::class);
        yield MenuItem::linkToCrud('Accueil Slider', 'fas fa-pager', AccueilSlider::class);
        yield MenuItem::linkToCrud('Transport', 'fas fa-truck', Transporteurs::class);
        yield MenuItem::linkToCrud('Orders', 'fas fa-cash-register', Orders::class);
        yield MenuItem::linkToCrud('Clients', 'fas fa-user', User::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
