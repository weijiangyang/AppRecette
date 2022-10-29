<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
       
           
            yield TextField::new('content');
            
            yield AssociationField::new('recipe','Recette')
            ->hideOnForm();
            yield AssociationField::new('user', 'Commenteur')
            ->hideOnForm();
           yield DateTimeField::new('createdAt')
            ->hideOnForm();
    
    }
    
}
