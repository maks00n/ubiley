<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\GuestList;
use App\Entity\Product;
use App\Entity\Tables;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(TablesCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Административная панель')
            ->renderContentMaximized()
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Столы', 'fas fa-table', Tables::class);
        yield MenuItem::linkToCrud('Гости', 'fas fa-users', GuestList::class);
        yield MenuItem::linkToCrud('Продукты', 'fas fa-cart-shopping', Product::class);
        yield MenuItem::linkToCrud('Категории', 'fas fa-list', Category::class);
        yield MenuItem::linkToUrl('API', 'fa fa-link', '/api')->setLinkTarget('_blank')
            ->setPermission('ROLE_ADMIN');
    }
}
