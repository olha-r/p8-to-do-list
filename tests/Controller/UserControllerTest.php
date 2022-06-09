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
        // retrieve the test user

        $testAdminUser = $userRepository->findOneByEmail('admin@gmail.com');
        //$testAdminUser = $userRepository->findOneByRoles('ROLE_ADMIN'); //by Roles???


        // simulate $testUser being logged in
        $client->loginUser($testAdminUser);
    }

    public function testUsersListPageForAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user

        $testAdminUser = $userRepository->findOneByEmail('admin@gmail.com');

        //$testAdminUser = $userRepository->findOneBy(['roles' => ['ROLE_ADMIN']]); //by Roles???

        // simulate $testUser being logged in
        $client->loginUser($testAdminUser);

        $client->request('GET', '/users/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testUserProfilePage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy([ 'email' => 'user1@domain.fr']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', 'users/12');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Profile de user1');
    }

    public function testEditUsers() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user

        $testAdminUser = $userRepository->findOneByEmail('admin@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testAdminUser);

        $crawler = $client->request('GET', '/users/12/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'EditedUser',
            'user[email]' => 'editeduser@hotmail.fr'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', "Superbe ! L'utilisateur a bien été modifié");
    }
}
