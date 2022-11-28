<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                
                'label' => 'Addresse email',
                
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 180])
                ]
            ])
           
            ->add('fullName',TextType::class,[
                'attr'=>[
                   
                    'minlength'=>2,
                    'maxlength'=>255
                ],
                'label'=>'Prenom/Nom',
                
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,'max'=>255])
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

            ->add('plainPassword', RepeatedType::class, [
               
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    
                    
                ],
                'second_options' => [
                    'label' => 'Confirmation',
                
                ],
            'constraints' => [
                new Assert\NotBlank(),
               
                new Assert\Regex("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$^", 'le mot de passe contient au moins 8 caractères , au moins une lettre et un chiffre')
            ], 
                'invalid_message' => 'Les mots de passe ne correspond pas'
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
