<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class LoginTest extends WebTestCase
{
    public function testIfLoginIsSuccessful(): void
    {
        $client = static::createClient();
        // get route by urlgenerator
        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get('router');
        $crawler = $client->request('GET',$urlGenerator->generate('security_login'));
        
        // form
        $form = $crawler->filter("form[name=login]")->form([
            '_username' => "admin@gmail.com",
            '_password' => "123"
        ]

        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('app_index');
        
    }

    public function testIfLoginFailedWhenPasswordIsWrong()
    {
        $client = static::createClient();
        // get route by urlgenerator
        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get('router');
        $crawler = $client->request('GET', $urlGenerator->generate('security_login'));

        // form
        $form = $crawler->filter("form[name=login]")->form(
            [
                '_username' => "admin@gmail.com",
                '_password' => "123_"
            ]

        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('security_login');
        $this->assertSelectorTextContains('div.alert-danger','Invalid credentials');
  
    }
}
