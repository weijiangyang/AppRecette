<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPlainPassword', PasswordType::class, [
               
                'label' => 'Mot de passe actuel',
                

            ])
            ->add('plainPassword', PasswordType::class, [

                'label' => 'New mot de passe ',
            ])
            ->add('confirmation', PasswordType::class, [

                'label' => 'Confirmation ',
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4',

                ],
                'label' => 'Modifier votre mot de passe'
            ]);
    }

   
}
