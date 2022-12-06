<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Form\MarkType;
use App\Entity\Comment;
use App\Form\RecipeType;
use App\Entity\SearchBar;
use App\Form\ChercheType;
use App\Form\CommentType;
use Cocur\Slugify\Slugify;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\LazyProxy\PhpDumper\NullDumper;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recipe_index', methods:['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(RecipeRepository $recipeRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $recipes = $paginator->paginate(
            $recipeRepository->findBy(['user' => $this->getUser()]),  /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recette/public/{id}', name: 'recette_index_public')]
    #[IsGranted('ROLE_USER')]
    public function indexPublic(int $id,Request $request, PaginatorInterface $paginator, RecipeRepository $recipeRepository,CategoryRepository $categoryRepository): Response
    {   
        $category = $categoryRepository->find($id);
        $searchBar = new SearchBar;
        $form = $this->createForm(ChercheType::class,$searchBar);
        $form->handleRequest($request);
        $searchContent = $form->getData()->getContent();
        
        
         
            if (!$form->isSubmitted()){
          
                $recipes = $paginator->paginate(
                    $recipeRepository->findPublicRecipe(null,$category),/* query NOT result */
                    $request->query->getInt('page', 1), /*page number*/
                    9 /*limit per page*/
                );
            } else {
                
                $recipes = $paginator->paginate(
                $recipes = $recipeRepository->findSearcheRecipe($searchContent,$category),/* query NOT result */
                    $request->query->getInt('page', 1), /*page number*/
                    9 /*limit per page*/
            );
            }
         
            

       
         return $this->render('pages/recipe/index_public.html.twig', [
            'recipes' => $recipes,
            'form'=>$form->createView(),
            'searchContent'=>$searchContent,
            'category'=> $category
            ]);
        
    }

    #[Route('/recette/{slug}',name:'recipe_show', methods:['GET','POST'])]
    public function show(String $slug,CommentRepository $commentRepository,RecipeRepository $recipeRepository,MarkRepository $markRepository,Request $request, EntityManagerInterface $em){
        $recipe = $recipeRepository->findOneBy(['slug' => $slug]);
        if (!$recipe) {
            $this->addFlash(
                'warning',
                'La recette en question n\'existe pas'
            );
            return $this->redirectToRoute('recipe_index');
        }
        if($recipe->isIsPublic() && $this->getUser() != null || !$recipe->isIsPublic() && $recipe->getUser() === $this->getUser()){
           

            $comment = new Comment($recipe);
            $formComment = $this->createForm(CommentType::class, $comment);
            $comments = $commentRepository->findBy(['recipe' => $recipe]);
            $mark = new Mark;
            $form = $this->createForm(MarkType::class, $mark);
            if ($recipe->getUser() !== $this->getUser()) {

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $mark = new Mark;
                    $mark->setMark($form->getData()->getMark())
                        ->setUser($this->getUser())
                        ->setRecipe($recipe);
                    $existingMark = $markRepository->findOneBy([
                        'user' => $this->getUser(),
                        'recipe' => $recipe
                    ]);
                    if ($existingMark) {
                        $em->remove($existingMark);
                    };
                    $em->persist($mark);
                    $em->flush();

                    $this->addFlash(
                        'success',
                        'Voter note a été bien compté'
                    );

                    return $this->redirectToRoute('recipe_show', [
                        'slug' => $recipe->getSlug()
                    ]);
                }
            }
// dd($form);
            return $this->render('pages/recipe/show.html.twig', [
                'recipe' => $recipe,
                'form' => $form->createView(),
                'formComment' => $formComment->createView(),
                'comments' => $comments
            ]);
  
       }elseif($recipe->isIsPublic() && $this->getUser() === null){
            $comments = $commentRepository->findBy(['recipe' => $recipe]);
            return $this->render('pages/recipe/show.html.twig', [
                'recipe' => $recipe,
               
                
                'comments' => $comments
            ]);
       }
          return $this->redirectToRoute('app_index');
    }
    

    #[Route('/recette/nouveau',name:'recipe_new' ,methods:['GET','POST'], priority:1)]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em){
       $recipe = new Recipe;

       $form = $this->createForm(RecipeType::class,$recipe);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());
            $recipe->setSlug((new Slugify())->slugify($recipe->getName()).'-'.uniqid());
            $em->persist($recipe);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre recette a bien été crée!'
            );

            return $this->redirectToRoute('recipe_show',[
                'slug'=>$recipe->getSlug()
            ]);
       }

        return $this->render('pages/recipe/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/recette/edit/{slug}', name: 'recipe_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(String $slug,Request $request, EntityManagerInterface $em, RecipeRepository $recipeRepository)
    {

        $recipe = $recipeRepository->findOneBy(['slug' => $slug]);
        if (!$recipe) {
            $this->addFlash(
                'warning',
                'La recette en question n\'existe pas'
            );
            return $this->redirectToRoute('recipe_index');
        }
        if($recipe->getUser() === $this->getUser()){
            $form = $this->createForm(RecipeType::class, $recipe);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $recipe = $form->getData();
                $recipe->setUpdatedAt(new \DateTimeImmutable());
                $recipe->setSlug((new Slugify())->slugify($recipe->getName()) . '-' . uniqid());
                $em->persist($recipe);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Vous avez bien modifié la recette avec succès !'
                );
                return $this->redirectToRoute('recipe_show', [
                    'slug' => $recipe->getSlug()
                ]);
            }
            return $this->render('pages/recipe/edit.html.twig', [
                'form' => $form->createView(),
                'recipe' => $recipe
            ]);
        }
        $this->addFlash(
            'warning',
            'Vous avez pas le droit de modifier cette recette!'
        );
        return $this->redirectToRoute('app_index');
       
    }

    #[Route('recipe/supprimer/{slug}', name: 'recipe_delete', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete( String $slug,EntityManagerInterface $em, RecipeRepository $recipeRepository)
    {
       $recipe = $recipeRepository->findOneBy(['slug' => $slug]);

        if (!$recipe) {
            $this->addFlash(
                'warning',
                'La recette en question n\'existe pas'
            );
            return $this->redirectToRoute('recipe_index');
        }
        if ($recipe->getUser() === $this->getUser()) {
            $em->remove($recipe);
            $em->flush();

            $this->addFlash(
                'success',
                'Vous avez bien supprimé la recette avec succès ! '
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->redirectToRoute('app_index');
        
    }
}
