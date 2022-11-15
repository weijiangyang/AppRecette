<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, 'new')
            ->remove(Crud::PAGE_INDEX,'edit');
    }

    
    public function configureFields(string $pageName): iterable
    {
         
            yield TextEditorField::new('content')
                        ->hideOnForm();
            yield AssociationField::new('recipe','Recette')
            ->hideOnForm();
            yield AssociationField::new('user', 'Commenteur')
            ->hideOnForm();
           yield DateTimeField::new('createdAt')
            ->hideOnForm();
    }
    
    
}
