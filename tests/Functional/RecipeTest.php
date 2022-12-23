<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeTest extends WebTestCase
{
    public function testIfCreateRecipeIsSuccessful(): void
    {
        $client = static::createClient();
        // Recup urlgenerator
        
        /**
         * @var UrlGeneratorInterface
         */
        $urlGenerator = $client->getContainer()->get('router');
        // Recup entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 1);
        $client->loginUser($user);
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('recipe_new'));
        // Se rendre sur la page de la création d'une recette
        // Gérer le formulaire
        $form = $crawler->filter('form[name=recipe]')->form(
            [
                'recipe[name]' => 'une recette' . uniqid(),
                'recipe[description]' => 'description',
                'recipe[isFavorite]' => true,
                'recipe[isPublic]' => true,
                'recipe[categories]' => ['1']


            ]
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Gérer la redirection
        $client->followRedirect();
        // Gérer l'alert box et la route 
        $this->assertSelectorTextContains('p.alert.alert-success', 'Votre recette a bien été crée');
        $this->assertRouteSame('recipe_show');
       
    }

    public function testIfListingRecipeIsSuccessful()
    {
        $client = static::createClient();
        // Recup urlgenerator
        /**
         * @var UrlGeneratorInterface
         */
        $urlGenerator = $client->getContainer()->get('router');
        // Recup entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 1);
        $client->loginUser($user);
        $client->request(Request::METHOD_GET, $urlGenerator->generate('recipe_index'));
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('recipe_index');
    }

    public function testIfUpdateRecipeIsSuccessful()
    {
        $client = static::createClient();
        // Recup urlgenerator
        /**
         * @var UrlGeneratorInterface
         */
        $urlGenerator = $client->getContainer()->get('router');
        // Recup entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 1);
        $client->loginUser($user);
        $recipe = $entityManager->getRepository(Recipe::class)->findOneBy(
            [
                'user' => $user
            ]
        );

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('recipe_edit', ['slug' => $recipe->getSlug()]));
        $this->assertResponseIsSuccessful();
        $form = $crawler->filter('form[name=recipe]')->form(
            [
                'recipe[name]' => 'une recette' . uniqid(),
                'recipe[description]' => 'description',
                'recipe[isFavorite]' => true,
                'recipe[isPublic]' => true,
                'recipe[categories]' => ['1']

            ]
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('p.alert.alert-success', 'Vous avez bien modifié la recette avec succès');
        $this->assertRouteSame('recipe_show');
    }

    public function testIfDeleteUneRecipeIsSuccessful()
    {
        $client = static::createClient();
        // Recup urlgenerator
        /**
         * @var UrlGeneratorInterface
         */
        $urlGenerator = $client->getContainer()->get('router');
        // Recup entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 1);
        $client->loginUser($user);
        $recipe = $entityManager->getRepository(Recipe::class)->findOneBy(
            [
                'user' => $user
            ]
        );

        $client->request(Request::METHOD_GET, $urlGenerator->generate('recipe_delete', ['slug' => $recipe->getSlug()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);


        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'Vous avez bien supprimé la recette avec succès');
        $this->assertRouteSame('recipe_index');
    }

    public function testIfCreateRecipeFailedIfNameExistInBdd()
    {
        $client = static::createClient();   
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $recipe = $entityManager->find(Recipe::class, 1);

        /**
         * @var UrlGeneratorInterface
         */
        $urlGenerator = $client->getContainer()->get('router');
        // Recup entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 1);
        $client->loginUser($user);
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('recipe_new'));
        // Se rendre sur la page de la création d'une recette
        // Gérer le formulaire
        $form = $crawler->filter('form[name=recipe]')->form(
            [
                'recipe[name]' => $recipe->getName(),
                'recipe[description]' => 'description',
                'recipe[isFavorite]' => true,
                'recipe[isPublic]' => true,
                'recipe[categories]' => ['1']


            ]
        );
        $client->submit($form);
        // verifier la route 
        $this->assertRouteSame('recipe_new');
        // verifier le message d'erreur
        $this->assertSelectorTextContains('div.form-error.text-danger', 'This value is already used.');

    }


}
