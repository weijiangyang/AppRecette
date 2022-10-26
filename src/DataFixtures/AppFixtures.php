<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr-FR');
        $this->hasher = $hasher;
    }

  
    public function load(ObjectManager $manager): void
    {
        //user
        for($k = 0; $k < 10; $k++){
            $user = new User;
            $user->setEmail($this->faker->email)
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password')
                ->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0,1) === 1?$this->faker->firstName():null);
            $manager->persist($user);
        }
        // ingredients
        $ingredients = [];
        for($i = 0; $i < 50 ; $i++){
            $ingredient = new Ingredient;
            $ingredient->setName($this->faker->word())
                    ->setPrice(mt_rand(0, 1) === 1 ? mt_rand(1, 150) : 0)
                    ->setUnit($this->faker->word());
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        //recipes:
        for($i = 0 ; $i < 25; $i++){
            $recipe = new Recipe;
            $recipe->setName($this->faker->word())
                ->setTime(mt_rand(0,1)=== 1? mt_rand(1,1440):null)
                ->setNbPeople(mt_rand(0, 1) === 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) === 1 ? mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(0, 1) === 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0,1));
            for($k = 0; $k < mt_rand(5,15) ; $k++){
                $recipe->addIngredient($ingredients[mt_rand(0,count($ingredients)-1)]);
            }

            $manager->persist($recipe);
        }
        
        $manager->flush();
    }
}
