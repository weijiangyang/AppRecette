<?php
namespace App\Tests\Functional\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactTest extends WebTestCase{
    public function testCrudIsHere():void
    {
        $client = static::createClient();
        /** 
         * @var EntityManagerInterface
        */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => 1]);
        $client->loginUser($user);
        $client->request(Request::METHOD_GET,'/admin');
        $this->assertResponseIsSuccessful();
        $crawler = $client->clickLink('Contacts');
        $client->click($crawler->filter('.action-delete')->link());

    }
}