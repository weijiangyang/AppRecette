<?php

namespace App\Tests\Funtional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Inscription');
        $this->assertEquals(2, count($link));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenu sur AppRecette');
        $recipes = $crawler->filter('.card');
        $this->assertEquals(6, count($recipes));

    }
}
