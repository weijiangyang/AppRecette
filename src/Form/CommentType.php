<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label'=> 'Votre commentaire',
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('recipe', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn-sm btn-primary btn'
                ],
                'label' => 'Envoyer'
            ]);
        $builder->get('recipe')
        ->addModelTransformer(new CallbackTransformer(
            fn (Recipe $recipe) => $recipe->getId(),
            fn (Recipe $recipe) => $recipe->getName()
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_token_id' => 'comment_add'
        ]);
    }
}
