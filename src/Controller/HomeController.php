<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
   
    #[Route('/', name: 'app_index',methods:['GET'])]
    public function index(RecipeRepository $recipeRepository, RecipeService $recipeService): Response
    {
        $recipesRecentes = $recipeRepository->findPublicRecipe(3,null);
      
        $recipesNotes = $recipeService->getRecipesNotes(3);
            return $this->render('pages/home/index.html.twig', [
                'recipesRecentes' => $recipesRecentes,
                'recipesNotes'=>$recipesNotes
            ]);
        }
    #[Route('/confidentialite', name:'app_confientialite', methods: ['GET'])]
    public function confidentialite(){
        return $this->render('politiques-confidentiality.html.twig');
    }

    #[Route('/mentionsLegales', name: 'app_mentions', methods: ['GET'])]
    public function mentions()
    {
        return $this->render('mentions-legales.html.twig');
    }

    #[Route('/mode-emploi', name: 'app_mode_emploi', methods: ['GET'])]
    public function modeEmploi()
    {
        return $this->render('mode-emploi.html.twig');
    }
}
