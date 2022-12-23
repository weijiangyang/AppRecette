<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class IngredientTest extends KernelTestCase
{

    private function getIngredient(): Ingredient
    {
        return (new Ingredient())
            ->setName('Ingredient #1')
            ->setDescription('Description #1')
            ->setUnit('kg')
            ->setPrice(1.0)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }
    public function testIngredientIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $ingredient = $this->getIngredient();

        $errors = $container->get('validator')->validate($ingredient);
        $this->assertCount(0, $errors);
    }

    public function testInvalidName()
    {
        self::bootKernel();
        $container = static::getContainer();
        $ingredient = $this->getIngredient();
        $ingredient->setName('');
        $errors = $container->get('validator')->validate($ingredient);
        $this->assertCount(2, $errors);
    }

    public function testInvalidPrice()
    {
        self::bootKernel();
        $container = static::getContainer();
        $ingredient = $this->getIngredient();
        $ingredient->setPrice(-1);
        $errors = $container->get('validator')->validate($ingredient);
        $this->assertCount(1, $errors);

    }

}
