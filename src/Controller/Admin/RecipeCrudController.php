<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, 'new');
            
           
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield NumberField::new('time','time(en minute)');
        yield NumberField::new('nbPeople','pour(personnes)');
        yield NumberField::new('difficulty', 'niveau de la difficulté/5');
        yield NumberField::new('price', 'price(€)');
        yield BooleanField::new('isPublic','Public?');
        yield TextEditorField::new('description');
        yield AssociationField::new('user','Créateur de la recette')
                ->hideOnForm();
               
        yield DateTimeField::new('createdAt')
        ->hideOnForm();
            
           
           
          
        
    }
    
}
