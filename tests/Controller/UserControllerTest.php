<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
//    public function getClientLoginAsUser()
//    {
//        $client = static::createClient();
//        $userRepository = static::getContainer()->get(UserRepository::class);
//
//        // retrieve the test user
//        $testUser = $userRepository->findOneByEmail('user0@domain.fr');
//
//        // simulate $testUser being logged in
//        $client->loginUser($testUser);
//    }


    public function getClientLoginAsAdmin()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testAdminUser = $userRepository->findOneByEmail('admin@gmail.com');

        $client->loginUser($testAdminUser);
    }

    public function testUsersListPageForAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testAdminUser = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($testAdminUser);

        $client->request('GET', '/users/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testUserProfilePage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([ 'email' => 'user1@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', 'users/33');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Profile de User');
    }

    public function testEditUsers() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testAdminUser = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($testAdminUser);

        $crawler = $client->request('GET', '/users/33/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'user_profile[username]' => 'EditedUser',
            'user_profile[email]' => 'editeduser@hotmail.fr'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', "Superbe ! L'utilisateur a bien été modifié");
    }

    public function testDeleteUsers()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testAdminUser = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($testAdminUser);

        $crawler = $client->request('POST', '/users/33/delete');
        $this->assertResponseRedirects();
        $client->followRedirect();
    }



}
