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
            $urls[] = [
                'loc' => $this->generateUrl('recipe_show', ['id' => $key]),
                'lastmod' => $value->getCreatedAt()->format('Y-m-d')
            ];
        }

        foreach ($recipeRepository->findAll() as $key => $value) {
            if($value->getUpdatedAt()){
                $urls[] = [
                    'loc' => $this->generateUrl('recipe_edit', ['id' => $key]),
                    'lastmod' => $value->getUpdatedAt()->format('Y-m-d')
                ];
            }else{
                $urls[] = [
                    'loc' => $this->generateUrl('recipe_edit', ['id' => $key]),
                    'lastmod' => $value->getCreatedAt()->format('Y-m-d')
                ];
            }
            
        }
        

        foreach ($ingredientRepository->findAll() as $key => $value) {
            $urls[] = [
                'loc' => $this->generateUrl('ingredient_show', ['id' => $key]),
                'lastmod' => $value->getCreatedAt()->format('Y-m-d')
            ];
        }

        foreach ($ingredientRepository->findAll() as $key => $value) {
            if($value->getUpdatedAt()){
                $urls[] = [
                    'loc' => $this->generateUrl('ingredient_edit', ['id' => $key]),
                    'lastmod' => $value->getUpdatedAt()->format('Y-m-d')
                ];
            }else{
                $urls[] = [
                    'loc' => $this->generateUrl('ingredient_edit', ['id' => $key]),
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
