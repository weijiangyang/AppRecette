<?php

namespace App\Form;

use App\Entity\Ingredient;
use Doctrine\DBAL\Types\DateTimeTzType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                
                'label'=>'Nom',
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,'max'=>255])
                ]
            ])
            ->add('unit',TextType::class,[
                
                'label' => 'Unit',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 255])
                ]
            ])
            ->add('price',MoneyType::class,[
                'label' => '* Prix',
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(200)
                ],
                'required'=>false
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label'=> '* Photo',
               

            ])
            ->add('description', CKEditorType::class, [
                'label' => '* Description',
                'required'=> false,
                'required' => false
                
            ])    
           
            ->add('button',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary my-3'
                ],
                'label'=> 'Créer l\'ingrédient'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);

        
    }
}
