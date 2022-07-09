<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
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

    public function testModifyUserRolesForAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testAdminUser = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($testAdminUser);

        $crawler = $client->request('GET', '/users/admin/2/modify');
        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Modifier role');
        $form = $button->form();
        // ticks a checkbox
        $form['user[roles]'][1]->tick();
        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', "Superbe ! Role d'utilisateur a bien été modifié");
        $this->assertSelectorTextContains('h1', 'Profile de user1');
    }

    public function testUserProfilePage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([ 'email' => 'user1@domain.fr']);
        $client->loginUser($testUser);

        $client->request('GET', 'users/2');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Profile de user1');
    }

    public function testEditUsers() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user1@domain.fr');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/users/2/edit');
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

        $crawler = $client->request('POST', '/users/3/delete');
        $this->assertResponseRedirects();
        $client->followRedirect();
    }



}
