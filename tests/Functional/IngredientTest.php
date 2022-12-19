<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Entity\Ingredient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IngredientTest extends WebTestCase
{
    public function testIfCreateIngredientIsSuccessful(): void
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
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('ingredient_new'));
        // Se rendre sur la page de la création d'un ingrédient
        // Gérer le formulaire
        $form = $crawler->filter('form[name=ingredient]')->form(
            [
                'ingredient[name]' => 'un ingredient new1234'.uniqid(),
                'ingredient[unit]' => 'kg',
                'ingredient[description]'=>'description',
            
               
            ]
            );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
              
        // Gérer la redirection
         $client->followRedirect();
        // Gérer l'alert box et la route 
        $this->assertSelectorTextContains('div.alert.alert-success', 'Votre ingrédient a bien été crée avec succès');
        $this->assertRouteSame('ingredient_index');
    }

    public function testIfListingIngredientIsSuccessful()
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
        $client->request(Request::METHOD_GET, $urlGenerator->generate('ingredient_index')); 
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('ingredient_index');
    }

    public function testIfUpdateIngredientIsSuccessful()
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
        $ingredient = $entityManager->getRepository(Ingredient::class)->findOneBy([
            'user' => $user
            ]
        );
      
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('ingredient_edit',['slug'=> $ingredient->getSlug()]));
        $this->assertResponseIsSuccessful();
        $form = $crawler->filter('form[name=ingredient]')->form(
            [
                'ingredient[name]' => 'un ingredient new1234' . uniqid(),
                'ingredient[unit]' => 'kg',
                'ingredient[description]' => 'description',


            ]
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'Votre ingrédient a bien été modifié avec succès');
        $this->assertRouteSame('ingredient_index');

    }

    public function testIfDeleteUnIngredientIsSuccessful()
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
        $ingredient = $entityManager->getRepository(Ingredient::class)->findOneBy(
            [
                'user' => $user
            ]
        );

        $client->request(Request::METHOD_GET, $urlGenerator->generate('ingredient_delete', ['slug' => $ingredient->getSlug()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
       
      
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'Vous avez bien supprimé un ingrédient avec succès');
        $this->assertRouteSame('ingredient_index');
    }
}
