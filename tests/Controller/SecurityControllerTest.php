<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLoginForm(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
//        $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();
        $crawler =$client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' =>'john@doe.fr',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertSelectorTextContains( 'div',"Invalid credentials.");
    }





    public function testLoginSuccessful()
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class]);
        $userRepository = static::$container->get(UserRepository::class);

        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => 'password'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects("", Response::HTTP_FOUND);
        $crawler = $client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSelectorTextContains('strong', "user");
        $this->assertSelectorTextContains('a.btn-danger', "Se déconnecter");

    }



}
