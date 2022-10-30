<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, 'new');
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield TextField::new('unit');
        yield NumberField::new('price','price(€)')->setNumDecimals(2);
        yield AssociationField::new('user', 'Créateur de l\'ingrédient')
                    ->hideOnForm();
        yield DateTimeField::new('createdAt')
        ->hideOnForm();
    }
    
}
