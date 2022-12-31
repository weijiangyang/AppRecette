<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Recettes')
            ->setEntityLabelInSingular('Recette')
            ->setPageTitle('index', "AppRecette - Administration des recettes")
            ->setPaginatorPageSize(5)
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setPaginatorPageSize(5);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield NumberField::new('time','time(en minute)');
        yield NumberField::new('nbPeople','pour(personnes)');
        yield NumberField::new('difficulty', 'niveau de la difficulté/5');
        yield NumberField::new('price', 'price(€)');
        yield BooleanField::new('isPublic','Public?');
        yield TextField::new('imageFile', 'Upload')
            ->setFormType(VichImageType::class)
            ->hideOnIndex();
        yield  ImageField::new('imageName', 'Image')
            ->setBasePath('/images/recette/')
            ->hideOnForm();
        yield TextEditorField::new('description')
            ->setFormType(CKEditorType::class);
        yield AssociationField::new('user','Créateur de la recette')
                ->hideOnForm();      
        yield DateTimeField::new('createdAt')
        ->hideOnForm(); 
    }
    
}
