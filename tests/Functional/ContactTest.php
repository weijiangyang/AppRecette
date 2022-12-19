<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactTest extends WebTestCase
{
    public function testIfSubmitContactFormIsSuccessful(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de Contact');

        // récupérer le formulaire
        $submitButton = $crawler->selectButton("Envoyer le message");
        $form = $submitButton->form();
       
        $form["contact[fullName]"] = "weijiangYang";
        $form["contact[email]"] = "weijiangyang@laposte.net";
        $form["contact[subject]"] = "Test";
        $form["contact[message]"] = "Test";
        
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
            'Votre message a bien été envoyé'
        );
    }

   
}
