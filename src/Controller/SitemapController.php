<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\ContactRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    /**
     * This function allow to display the page 'sitemap.xml'
     *
     * @param Request $request
     * @param RecipeRepository $recipeRepository
     * @param IngredientRepository $ingredientRepository
     * @param ContactRepository $contactRepository
     * @return Response
     */
    #[Route('/sitemap.xml', name: 'app_sitemap', defaults:['_format'=>'xml'])]
    public function index(Request $request, 
    RecipeRepository $recipeRepository,
    IngredientRepository $ingredientRepository,
    ContactRepository $contactRepository): Response
    {
        $hostname = $request->getSchemeAndHttpHost();
   
        $urls = [];
        $urls[] = ['loc' => $this->generateUrl('app_index')];
        $urls[] = ['loc' => $this->generateUrl('recipe_index')];
        $urls[] = ['loc' => $this->generateUrl('recipe_new')];
        $urls[] = ['loc' => $this->generateUrl('ingredient_index')];
        $urls[] = ['loc' => $this->generateUrl('ingredient_new')];
        $urls[] = ['loc' => $this->generateUrl('contact_index')];
        $urls[] = ['loc' => $this->generateUrl('comment_add')];
       

        foreach ($recipeRepository->findAll() as $key => $value) {
            $slug = $value -> getSlug();
            $urls[] = [
                'loc' => $this->generateUrl('recipe_show', ['slug' => $slug]),
                'lastmod' => $value->getCreatedAt()->format('Y-m-d')
            ];
        }

        foreach ($recipeRepository->findAll() as $key => $value) {
            $slug = $value->getSlug();
            if($value->getUpdatedAt()){
                $urls[] = [
                    'loc' => $this->generateUrl('recipe_edit', ['slug' => $slug]),
                    'lastmod' => $value->getUpdatedAt()->format('Y-m-d')
                ];
            }else{
                $urls[] = [
                    'loc' => $this->generateUrl('recipe_edit', ['slug' => $slug]),
                    'lastmod' => $value->getCreatedAt()->format('Y-m-d')
                ];
            }
            
        }
        

        foreach ($ingredientRepository->findAll() as $key => $value) {
            $slug = $value -> getSlug();
            $urls[] = [
                'loc' => $this->generateUrl('ingredient_show', ['slug' => $slug]),
                'lastmod' => $value->getCreatedAt()->format('Y-m-d')
            ];
        }

        foreach ($ingredientRepository->findAll() as $key => $value) {
            $slug = $value -> getSlug();
            if($value->getUpdatedAt()){
                $urls[] = [
                    'loc' => $this->generateUrl('ingredient_edit', ['slug' => $slug]),
                    'lastmod' => $value->getUpdatedAt()->format('Y-m-d')
                ];
            }else{
                $urls[] = [
                    'loc' => $this->generateUrl('ingredient_edit', ['slug' => $slug]),
                    'lastmod' => $value->getCreatedAt()->format('Y-m-d')
                ];
            }
            
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
        );
        return $response;
    }
}
