<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    
                    'minlength' => 2,
                    'maxlength' => 255
                ],
                'label' => 'Prenom/Nom',
                
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 255])
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                   
                    'minlength' => 2,
                    'maxlength' => 255
                ],
                'label' => 'Pseudo',
                
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 255])
                ]
            ])
           
            ->add('description', CKEditorType::class, [
                'label' => 'Description',
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])    
            ->add('plainPassword', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control',

                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form-label  mt-4',
                ]

            ])
            
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4',

                ],
                'label' => 'Modifier le profile'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

