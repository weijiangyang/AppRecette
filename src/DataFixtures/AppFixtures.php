<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Mark;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Contact;
use App\Entity\Category;
use App\Entity\Ingredient;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    private $categoryRepository;
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct(CategoryRepository $categoryRepository, UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr-FR');
        $this->hasher = $hasher;
        $this->categoryRepository = $categoryRepository;
    }


    public function load(ObjectManager $manager): void
    {
        //user
        $users = [];
        for ($k = 0; $k < 10; $k++) {
            $user = new User;
            $user->setEmail($this->faker->email)
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password')
                ->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null);
            $manager->persist($user);
            $users[] = $user;
        }
        // ingredients
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient;
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 1) === 1 ? mt_rand(1, 150) : 0)
                ->setUser($users[mt_rand(0, count($users) - 1)])
                ->setUnit($this->faker->word());
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        //categories
        $categories = [];
        $category = new Category;
        $category->setName('chinoise');
        $manager->persist($category);
        $categories[] = $category;

        $category = new Category;
        $category->setName('française');
        $manager->persist($category);
        $categories[] = $category;


        //recipes:
        $recipes = [];
        for ($i = 0; $i < 25; $i++) {
            $recipe = new Recipe;
            $recipe->setName($this->faker->word())
                ->setTime(mt_rand(0, 1) === 1 ? mt_rand(1, 1440) : null)
                ->setNbPeople(mt_rand(0, 1) === 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) === 1 ? mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(300))
                ->addCategory($categories[mt_rand(0, count($categories) - 1)])
                ->setPrice(mt_rand(0, 1) === 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
                ->setIsPublic(true)
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }
            $manager->persist($recipe);
            $recipes[] = $recipe;
        }

        //marks

        foreach ($recipes as $recipe) {
            $userschosen = [];
            for ($i = 0; $i < mt_rand(3, 10); $i++) {
                $userschosen[] = $users[mt_rand(0, count($users) - 1)];
            }
            $userschosen = array_unique($userschosen);

            foreach ($userschosen as $userchosen) {
                $mark = new Mark;
                $mark->setMark(mt_rand(1, 5))
                    ->setUser($userchosen)
                    ->setRecipe($recipe);
                $manager->persist($mark);
            }
        }

        //Contacts

        for ($i = 0; $i < 5; $i++) {
            $contact = new Contact;
            $contact->setFullName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setSubject('Demande N° ' . $i)
                ->setMessage($this->faker->text());
            $manager->persist($contact);
        }

        $manager->flush();
    }
}
