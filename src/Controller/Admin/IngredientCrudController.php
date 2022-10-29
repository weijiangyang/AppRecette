<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield TextField::new('unit');
        yield NumberField::new('price','price(€)')->setNumDecimals(2);
        yield AssociationField::new('user', 'Créateur de l\'ingrédient');
        yield DateTimeField::new('createdAt')
        ->hideOnForm();
    }
    
}
