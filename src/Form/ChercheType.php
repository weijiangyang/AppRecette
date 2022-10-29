<?php

namespace App\Form;

use App\Entity\Mark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints as Assert;

class ChercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'attr'=>[
                    'class'=> 'form-control me-sm-2 ',
                    'placeholder'=> 'search',
                    'value'=>null
                ],
                'label_attr'=>[
                    'class'=>'d-none'
                ],
                'required'=>false
               
                
               
             ])   
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning btn-sm my-2 my-sm-0'
                ],
                'label' => 'Search'
            ]);
           
            
    }

   
}


