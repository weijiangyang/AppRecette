<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Cateégories')
            ->setEntityLabelInSingular('Catégorie')
            ->setPageTitle('index', 'AppRecette - Administration des catégories')

            ->setPaginatorPageSize(5);
    }

    
    public function configureFields(string $pageName): iterable
    {
            
            yield TextField::new('name');
            yield DateTimeField::new('createdAt')
                    ->hideOnForm();
            
        
    }
    
}
