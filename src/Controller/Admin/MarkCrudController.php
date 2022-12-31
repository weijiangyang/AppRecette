<?php

namespace App\Controller\Admin;

use App\Entity\Mark;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MarkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mark::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Notes')
            ->setEntityLabelInSingular('Note')
            ->setPageTitle('index', 'AppRecette - Administration des notes')

            ->setPaginatorPageSize(5);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, 'new')
            ->remove(Crud::PAGE_INDEX,'edit');
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield NumberField::new('mark','note');

        yield AssociationField::new('recipe', 'Recette')
        ->hideOnForm();
        yield AssociationField::new('user', 'Commenteur')
        ->hideOnForm();
        yield DateTimeField::new('createdAt')
        ->hideOnForm();
   
            
            
            
        
    }
    
}
