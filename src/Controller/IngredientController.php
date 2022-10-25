<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * This function display all ingredients     
     * @param Request $request
     * @param IngredientRepository $ingredientRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient_index',methods:['GET'])]
    public function index(Request $request,IngredientRepository $ingredientRepository, PaginatorInterface $paginator): Response
    {
      
        $ingredients = $paginator->paginate(
            $ingredientRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/ingredient/index.html.twig', [
           'ingredients' => $ingredients
        ]);
    }
    
    /**
     * This function permet de create a new ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return void
     */
    #[Route('/ingredient/nouveau', name: 'ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em){
        $ingredient = new Ingredient;
        $form = $this->createForm(IngredientType::class,$ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $em->persist($ingredient);
            $em->flush();
            $this->addFlash(
                'success',
                'Votre ingrédient a bien été crée avec succès!'
            );
            
            return $this->redirectToRoute('ingredient_index');
        }
        return $this->render('pages/ingredient/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    
    /**
     * This function permet de edit a ingredient
     *
     * @param integer $id
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param IngredientRepository $ingredientRepository
     * @return void
     */
    #[Route('/ingredient/edit/{id}', name: 'ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, Ingredient $ingredient){
        

        $form = $this->createForm(IngredientType::class,$ingredient);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();
            $em->persist($ingredient);
            $em->flush();
        $this->addFlash(
            'success',
            'Vous avez bien modifié l\'ingredient avec succès !'
        );
        return $this->redirectToRoute('ingredient_index');
        }
        return $this->render('pages/ingredient/edit.html.twig',[
            'form' => $form->createView(),
            'ingredient'=>$ingredient
        ]);
    }
   
    /**
    
     * @param EntityManagerInterface $em
     * @param Ingredient $ingredient
     * @return void
     */
     #[Route('ingredient/supprimer/{id}', name:'ingredient_delete', methods:['GET','POST'])]
    public function delete(int $id,EntityManagerInterface $em, IngredientRepository $ingredientRepository){
        $ingredient = $ingredientRepository->find($id);
        if(!$ingredient){
            $this->addFlash(
                'warning',
                'L\'ingrédient en question n\'existe pas'
            );
            return $this->redirectToRoute('ingredient_index');
        }
        $em->remove($ingredient);
        $em->flush();

        $this->addFlash(
            'success',
            'Vous avez bien supprimé un ingrédient avec succès ! '
        );

        return $this->redirectToRoute('ingredient_index');

    }
}
