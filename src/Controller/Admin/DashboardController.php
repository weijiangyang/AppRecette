<?php

namespace App\Controller\Admin;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Category;
use App\Entity\Ingredient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AppRecette - Administration')
            ->renderContentMaximized();
    }


    public function configureMenuItems(): iterable

    {
        yield MenuItem::linkToRoute('Aller sur le site', 'fa fa-undo', 'app_index');
      
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Contacts', 'fas fa-envelope', Contact::class);
        yield MenuItem::linkToCrud('Catégories', 'fa-brands fa-delicious', Category::class);
        yield MenuItem::linkToCrud('Comments', 'fa-solid fa-comment', Comment::class);
        yield MenuItem::linkToCrud('Recettes', 'fa-solid fa-bowl-food', Recipe::class);
        yield MenuItem::linkToCrud('Ingrédients', 'fa-brands fa-elementor', Ingredient::class);
        yield MenuItem::linkToCrud('Notes', 'fa-solid fa-star', Mark::class);

    }
    
}
