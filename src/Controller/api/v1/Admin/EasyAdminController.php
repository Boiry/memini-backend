<?php

namespace App\Controller\api\v1\Admin;

use\App\Entity\Comment;
use App\Entity\Memini;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EasyAdminController extends AbstractDashboardController
{
    /**
     * @Route("/app/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        return $this->render('admin.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Memini');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Memini', 'fas fa-list', Memini::class);
        yield MenuItem::linkToCrud('Comment', 'fas fa-list', Comment::class);
    }
}
