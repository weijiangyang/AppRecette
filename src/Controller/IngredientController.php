<?php

namespace App\Controller;

use App\Entity\Ingredient;
use Cocur\Slugify\Slugify;
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
     * This function display all ingredients created by the current user    
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
    
   
    /**
     * This function allow to trouver a specific ingredient with its slug from the bdd
     *
     * @param String $slug
     * @param IngredientRepository $ingredientRepository
     * @return void
     */
    #[Route('/ingredient/{slug}', name: 'ingredient_show', methods: ['GET', 'POST'])]
    public function show(String $slug,IngredientRepository $ingredientRepository)
    {
        $ingredient = $ingredientRepository->findOneBy(['slug' => $slug]);
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
        if($ingredientsParUser){
            foreach($ingredientsParUser as $ingredientParUser){
                $ingredientsNames[] = $ingredientParUser->getName();
            }
        }
        

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $ingredient->setSlug((new Slugify())->slugify($ingredient->getName()) . '-' . uniqid());
            if($ingredientsNames){
                if (!in_array($ingredient->getName(), $ingredientsNames)) {
                    $ingredient->setUser($this->getUser());
                    $em->persist($ingredient);
                    $em->flush();
                    $this->addFlash(
                        'success',
                        'Votre ingrédient a bien été crée avec succès!'
                    );

                    return $this->redirectToRoute('ingredient_index', [
                        'error' => null
                    ]);
                } else {
                    return $this->render('pages/ingredient/new.html.twig', [
                        'form' => $form->createView(),
                        'error' => 'le nom de cette ingredient a déjà existé . '
                    ]);
                }
            }else{
                $ingredient->setUser($this->getUser());
                $em->persist($ingredient);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Votre ingrédient a bien été crée avec succès!'
                );

                return $this->redirectToRoute('ingredient_index', [
                ]);

            }
            
           
        }
        return $this->render('pages/ingredient/new.html.twig',[
            'form'=>$form->createView(),
            'error'=> null
           
        ]);
    }

    
    /**
     * This function permet to  edit an ingredient
     *
     * @param integer $id
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param IngredientRepository $ingredientRepository
     * @return void
     */
    #[Route('/ingredient/edit/{slug}', name: 'ingredient_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, EntityManagerInterface $em, String $slug, IngredientRepository $ingredientRepository){
        
        $ingredient = $ingredientRepository->findOneBy(['slug' => $slug]);
        if (!$ingredient) {
            $this->addFlash(
                'warning',
                'L\'ingrédient en question n\'existe pas!'
            );
            return $this->redirectToRoute('app_index');
        }
        if($ingredient->getUser() === $this->getUser()){
            $form = $this->createForm(IngredientType::class, $ingredient);
            $form->handleRequest($request);

            $ingredientsParUser = $ingredientRepository->findBy([
                'user' => $this->getUser(),

            ]);

            $sizeArray = count($ingredientsParUser);

            for ($i = 0; $i < $sizeArray; $i++) {
                if ($ingredientsParUser[$i] == $ingredient) {
                    unset($ingredientsParUser[$i]);
                }
            }

            $ingredientsNames = [];
            if ($ingredientsParUser) {
                foreach ($ingredientsParUser as $ingredientParUser) {
                    $ingredientsNames[] = $ingredientParUser->getName();
                }
            }


            if ($form->isSubmitted() && $form->isValid()) {
                $ingredient = $form->getData();
                if ($ingredientsNames) {
                    if (!in_array($ingredient->getName(), $ingredientsNames)) {
                        $ingredient->setUser($this->getUser());
                        $ingredient->setUpdatedAt(new \DateTimeImmutable());
                        $ingredient->setSlug((new Slugify())->slugify($ingredient->getName()) . '-' . uniqid());
                        $em->persist($ingredient);
                        $em->flush();
                        $this->addFlash(
                            'success',
                            'Votre ingrédient a bien été modifié avec succès!'
                        );

                        return $this->redirectToRoute('ingredient_index', [
                            'error' => null
                        ]);
                    } else {
                        return $this->render('pages/ingredient/new.html.twig', [
                            'form' => $form->createView(),
                            'error' => 'le nom de cette ingredient a déjà existé . '
                        ]);
                    }
                } else {
                    $ingredient->setUser($this->getUser());
                    $em->persist($ingredient);
                    $em->flush();
                    $this->addFlash(
                        'success',
                        'Votre ingrédient a bien été modifié avec succès!'
                    );

                    return $this->redirectToRoute('ingredient_index', []);
                }
            }
            return $this->render('pages/ingredient/edit.html.twig', [
                'form' => $form->createView(),
                'ingredient' => $ingredient
            ]);
        }
        $this->addFlash(
            'warning',
            'Vous n\'avez pas le droit de modifier cette ingrédient! '
        );
      return $this->redirectToRoute('app_index');
       
    }
   
    /**
     * This function allow to delete une ingredient
     * @param EntityManagerInterface $em
     * @param Ingredient $ingredient
     * @return void
     */
    #[Route('ingredient/supprimer/{slug}', name:'ingredient_delete', methods:['GET','POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(String $slug,EntityManagerInterface $em, IngredientRepository $ingredientRepository){

        $ingredient = $ingredientRepository -> findOneBy(['slug' => $slug]);
        if(!$ingredient){
            $this->addFlash(
                'warning',
                'L\'ingrédient en question n\'existe pas'
            );
            return $this->redirectToRoute('ingredient_index');
        }
    if($ingredient -> getUser() === $this -> getUser()){
            $em->remove($ingredient);
            $em->flush();

            $this->addFlash(
                'success',
                'Vous avez bien supprimé un ingrédient avec succès ! '
            );

            return $this->redirectToRoute('ingredient_index');
    }

        $this->addFlash(
            'warning',
            'Vous n\'avez pas le droit de supprimer cette ingrédient! '
        );
        return $this->redirectToRoute('app_index');

        

    }
}
