<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Flex\Update\RecipePatcher;

class RecipeService
{
    private $recipeRepository;
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * This function allow to finds these reicpes publics  which own the best notes 
     *
     * @param integer|null $nb
     * @return array
     */
    public function getRecipesNotes(?int $nb):array
    {
        $recipesNotes = $this->recipeRepository->findby(['isPublic' => 1]);
        $arrayRecipesNotes = [];
        foreach ($recipesNotes as $recipe) {
            $arrayRecipesNotes[$recipe->getId()] =  $recipe->getAverage();
        }
        arsort($arrayRecipesNotes);
        $recipesNotes = [];
        foreach ($arrayRecipesNotes as $key => $value) {
            $recipesNotes[] = $key;
        }
        $recipesNotesId = array_slice($recipesNotes, 0, $nb);
        // dd($recipes);
        $recipes = [];
        foreach ($recipesNotesId as $id) {
            $recipes[] = $this->recipeRepository->find($id);
        }
        return $recipes;
    }
    
}
