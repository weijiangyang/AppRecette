<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    public function testIfInscriptionIsSuccessful(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire d\'inscription');

        // récupérer le formulaire
        $submitButton = $crawler->selectButton("Inscrivez vous");
        $form = $submitButton->form();

        $form["registration[email]"] = "weijiangYang@laposte.net";
        $form["registration[fullName]"] = "weijiangyang";
        $form["registration[pseudo]"] = "Test";
        $form["registration[plainPassword][first]"] = "password1";
        $form["registration[plainPassword][second]"] = "password1";


        // soumettre le formulaire
        $client->submit($form);
        // verifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // verifier l'envoie du mail
        $this->assertEmailCount(1);
        $client->followRedirect();
        // verifier la présence du message du succes
        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            'Votre compte a bien été registré'
        );
        $this->assertRouteSame('security_login');
    }

    public function testIfInscriptionFailedWhenPasswordConfirmationIsWrong()
    {
        $client = static::createClient();
        // get route by urlgenerator
        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get('router');
        $crawler = $client->request('GET', $urlGenerator->generate('security_registration'));

        // form
        $form = $crawler->filter("form[name=registration]")->form();

        $form["registration[email]"] = "weijiangYang1@laposte.net";
        $form["registration[fullName]"] = "weijiangyang";
        $form["registration[pseudo]"] = "Test";
        $form["registration[plainPassword][first]"] = "password1";
        $form["registration[plainPassword][second]"] = "password";

        $client->submit($form);
       
        $this->assertRouteSame('security_registration');
        $this->assertSelectorTextContains('div.invalid-feedback.d-block', 'Les mots de passe ne correspond pas');
    }

    public function testIfInscriptionFailedWhenEmailExistInBdd()
    {
        $client = static::createClient();
        // Recup entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 1);
        $crawler = $client->request('GET', '/inscription');

        // récupérer le formulaire
        $submitButton = $crawler->selectButton("Inscrivez vous");
        $form = $submitButton->form();

        $form["registration[email]"] = $user->getEmail();
        $form["registration[fullName]"] = "weijiangyang";
        $form["registration[pseudo]"] = "Test";
        $form["registration[plainPassword][first]"] = "password1";
        $form["registration[plainPassword][second]"] = "password1";

        // soumettre le formulaire
        $client->submit($form);

        // verifier la route
        $this->assertRouteSame('security_registration');
        // verifier le message d'erreur
        $this->assertSelectorTextContains('div.invalid-feedback.d-block', 'This value is already used.');



        
    }
}
