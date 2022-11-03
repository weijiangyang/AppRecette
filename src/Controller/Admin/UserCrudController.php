<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private $hasher;
    private $entityRepository;

    public function __construct(UserPasswordHasherInterface $hasher, EntityRepository $entityRepository)
    {
        $this->hasher = $hasher;
        $this->entityRepository = $entityRepository;
    }
    
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')
            ->setPageTitle('index', 'AppRecette - Administration des utilisateurs')
            ->setPaginatorPageSize(5)
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
            
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, 'new');
           
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('fullName')
                    ->setDisabled(true);
        yield TextField::new('pseudo')
            ->setDisabled(true);
        
                
        yield EmailField::new('email')
        ->setDisabled(true);
        yield TextEditorField::new('description')
        ->setFormType(CKEditorType::class)
        ->setDisabled(true);
               
       
        
        yield ChoiceField::new('roles')
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_ADMIN' => 'success',
                
                'ROLE_USER' => 'info'
            ])
            ->setChoices([
                'Administrateur' => "ROLE_ADMIN",
                
                'Utilisateur' => "ROLE_USER"
            ]);
        yield DateTimeField::new('createdAt')
        ->hideOnForm();
        
    }
    

   

    
}
