<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testDisplayRegistrationPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');
    }

    public function testCreateUser():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'registration_form[username]' => 'User',
            'registration_form[email]' => 'user1@domain.com',
            'registration_form[password][first]' => 'password',
            'registration_form[password][second]' => 'password'
        ]);


        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
//
//        $testUser = static::$container->get(UserRepository::class)->findOneByUsername('User');
//        $this->assertInstanceOf(User::class,$testUser);

//        $userRepository = static::$container->get(UserRepository::class);
//        $userCreated = $userRepository->findOneBy(['email'=> 'username16@email.com']);
//        $crawler = $client->request('POST', '/users/'.$userCreated->getId().'/delete');

    }

}
