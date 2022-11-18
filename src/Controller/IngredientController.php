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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    #[IsGranted('ROLE_USER')]
    public function index(Request $request,IngredientRepository $ingredientRepository, PaginatorInterface $paginator): Response
    {
      
        $ingredients = $paginator->paginate(
            $ingredientRepository->findBy(['user'=>$this->getUser()]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/ingredient/index.html.twig', [
           'ingredients' => $ingredients
        ]);
    }

    #[Route('/ingredient/{id}', name: 'ingredient_show', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function show(Ingredient $ingredient)
    {
        if (!$ingredient) {
            return $this->redirectToRoute('app_index');
        }

         
                

        return $this->render('pages/ingredient/show.html.twig', [
           'ingredient'=> $ingredient
        ]);
    }
    
    /**
     * This function permet de create a new ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return void
     */
    #[Route('/ingredient/nouveau', name: 'ingredient_new', methods: ['GET', 'POST'], priority:2)]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em, IngredientRepository $ingredientRepository){
        $ingredient = new Ingredient;
        $form = $this->createForm(IngredientType::class,$ingredient);
        $form->handleRequest($request);

        $ingredientsParUser = $ingredientRepository->findBy([
            'user'=> $this->getUser()
        ]);

        $ingredientsNames = [];
        foreach($ingredientsParUser as $ingredientParUser){
            $ingredientsName[] = $ingredientParUser->getName();
        }

        

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            if(!in_array($ingredient->getName(),$ingredientsName) ){
                $ingredient->setUser($this->getUser());
                $em->persist($ingredient);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Votre ingrédient a bien été crée avec succès!'
                );

                return $this->redirectToRoute('ingredient_index',[
                    'error'=> null
                ]);
            }else{
                return $this->render('pages/ingredient/new.html.twig', [
                    'form' => $form->createView(),
                    'error' => 'le nom de cette ingredient a déjà existé . '
                ]);
            }
           
        }
        return $this->render('pages/ingredient/new.html.twig',[
            'form'=>$form->createView(),
            'error'=> null
           
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
    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
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
        return $this->redirectToRoute('ingredient_show',[
            'id'=>$ingredient->getId()
        ]);
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
    #[Security( "is_granted('ROLE_USER') and user === ingredient.getUser()" )]
    public function delete(EntityManagerInterface $em, Ingredient $ingredient){

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
