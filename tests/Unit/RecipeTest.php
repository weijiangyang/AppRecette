<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class RecipeTest extends KernelTestCase
{
    
    private function getRecipe(): Recipe
    {
        return (new Recipe())
            ->setName('Recipe #1')
            ->setDescription('Description #1')
            ->setIsFavorite(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

    }
    public function testRecipeIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $recipe = $this->getRecipe();

        $errors = $container -> get('validator')->validate($recipe);
        $this->assertCount(0,$errors);
    }

    public function testInvalidName(){
        self::bootKernel();
        $container = static::getContainer();
        $recipe = $this->getRecipe();
        $recipe->setName('');
        $errors = $container->get('validator')->validate($recipe);
        $this->assertCount(2, $errors);

    }

    public function testGetAverage()
    {
        $recipe =$this->getRecipe();
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class,1);
        for($i = 0; $i < 5; $i++){
            $mark = new Mark;
            $mark->setMark(2)
                 ->setUser($user)
                 ->setRecipe($recipe);
            $recipe->addMark($mark);
        }
        $this->assertTrue(2.0 === $recipe->getAverage());

    }

   
}
