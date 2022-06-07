<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLoginForm(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testLoginSuccessful()
    {
        $client = static::createClient();
        $crawler =$client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' =>'admin@gmail.com',
            '_password' => 'password'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

    }

        public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();
        $crawler =$client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' =>'user@gmail.com',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-danger', "User not found!");
    }

    public function testLogout(): void
    {
        $client = static::createClient();
        $client->request('GET', '/logout');
        // Check that user is not authenticated
        $this->assertFalse(unserialize($client->getContainer()->get('session')->get('_security_main')));
        // Check if user is redirected to login page
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSelectorTextContains('.btn.btn-success', "Se connecter");
    }

}
