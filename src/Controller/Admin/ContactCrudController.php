<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Contacts')
            ->setEntityLabelInSingular('Contact')
            ->setPageTitle('index', 'AppRecette - Administration de contacts')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setPaginatorPageSize(5);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return  [
            IdField::new('id')
            ->hideOnForm(),
            TextField::new('fullName'),
            TextField::new('email'),
            TextField::new('subject'),
            TextEditorField::new('message')
            ->setFormType(CKEditorType::class),
            DateTimeField::new('createdAt')
        ];
           
        
    }
    
}
