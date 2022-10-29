<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield NumberField::new('time');
        yield NumberField::new('nbPeople','pour combient de personnes');
        yield BooleanField::new('isPublic','Public?');
        yield AssociationField::new('user','Créateur de la recette');
        yield DateTimeField::new('createdAt')
        ->hideOnForm();
            
           
           
          
        
    }
    
}
