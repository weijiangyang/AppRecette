<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recipe_index', methods:['GET'])]
    public function index(RecipeRepository $recipeRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $recipes = $paginator->paginate(
            $recipeRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('recette/nouveau',name:'recipe_new' ,methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em){
       $recipe = new Recipe;

       $form = $this->createForm(RecipeType::class,$recipe);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();
            $em->persist($recipe);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre recette a bien été crée!'
            );

            return $this->redirectToRoute('recipe_index');
       }

        return $this->render('pages/recipe/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/recette/edit/{id}', name: 'recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, Recipe $recipe)
    {


        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $em->persist($recipe);
            $em->flush();
            $this->addFlash(
                'success',
                'Vous avez bien modifié la recette avec succès !'
            );
            return $this->redirectToRoute('recipe_index');
        }
        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe
        ]);
    }

    #[Route('recipe/supprimer/{id}', name: 'recipe_delete', methods: ['GET', 'POST'])]
    public function delete(int $id, EntityManagerInterface $em, RecipeRepository $recipeRepository)
    {
        $recipe = $recipeRepository->find($id);
        if (!$recipe) {
            $this->addFlash(
                'warning',
                'La recette en question n\'existe pas'
            );
            return $this->redirectToRoute('recipe_index');
        }
        $em->remove($recipe);
        $em->flush();

        $this->addFlash(
            'success',
            'Vous avez bien supprimé la recette avec succès ! '
        );

        return $this->redirectToRoute('recipe_index');
    }
}
