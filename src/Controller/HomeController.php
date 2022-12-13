<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * This funtion allow to display 3 recipes the most recent and 3 recipes the best noted dynamiquely
     *
     * @param RecipeRepository $recipeRepository
     * @param RecipeService $recipeService
     * @return Response
     */
    #[Route('/', name: 'app_index',methods:['GET'])]
    public function index(RecipeRepository $recipeRepository, RecipeService $recipeService): Response
    {
        // les recettes plus recentes
        $recipesRecentes = $recipeRepository->findPublicRecipe(3,null);

        // les recettes avec les notes meilleurs
        $recipesNotes = $recipeService->getRecipesNotes(3);

        return $this->render('pages/home/index.html.twig', [
            'recipesRecentes' => $recipesRecentes,
            'recipesNotes'=>$recipesNotes
        ]);
    }

    /**
     * This function allow to display the page "Politiques de la confidentialité"
     *
     * @return void
     */ 
    #[Route('/confidentialite', name:'app_confientialite', methods: ['GET'])]
    public function confidentialite(){
        return $this->render('politiques-confidentiality.html.twig');
    }

    /**
     * This function allow to display the page "Mentions légales"
     *
     * @return void
     */
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
