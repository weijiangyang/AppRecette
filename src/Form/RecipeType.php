<?php

namespace App\Form;

use App\Entity\Recipe;


use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'minlength' => 2,
                    'maxlength' => 255
                ],
                'label' => 'Nom',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 255])
                ]
            ])
            ->add('time',IntegerType::class,[
                'label'=> 'temps(minutes)',
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\Length(['min' => 1, 'max' => 1440])
                ]
            ])
            ->add('nbPeople', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\Length(['min' => 1, 'max' => 50])
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'attr'=>[
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Niveau de la difficulté',
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\Length(['min' => 1, 'max' => 5])
                ]
            ])
            ->add('description',CKEditorType::class,[
                'label'=> 'Les etapes de process',
                'constraints' => [
                    new Assert\NotBlank()
                ]   
            ])    
            ->add('price',MoneyType::class,[
                'label'=> 'Prix(€)',
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\Length(['min' => 1, 'max' => 1000])
                ],
                'required'=>false
            ])
            ->add('isFavorite', CheckboxType::class,[
                    'attr' => [
                        'class' => '',
                    ],
                    'label' => 'Favorite?',
                   
                    

                   
                ]
               

            )
                

            ->add(
                'ingredients',
                EntityType::class,
                [
                    'attr'=>[
                        'class'=> 'form-control d-flex justify-content-start flex-wrap '
                    ],
                    'class' => Ingredient::class,
                    'choice_label' => 'name',
                    'query_builder' => function (IngredientRepository $ingredientRepository) {
                        return $ingredientRepository->createQueryBuilder('i')
                                                    ->orderBy('i.name', 'ASC');
                    },
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Ingrédients',
                    
                    
                    'constraints' => [
                        new Assert\NotBlank(),
                    ]
                ]
            )
               
            
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary my-3'
                ],
                'label' => 'Créer ma recette'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
