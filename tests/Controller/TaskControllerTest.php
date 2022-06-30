<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    public function testTaskListPage()
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/');
        $this->assertResponseIsSuccessful();

    }

    public function testMyTaskListPage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy([ 'email' => 'user3@domain.fr']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks/');
        $this->assertResponseIsSuccessful();

    }


    public function testCreateTask(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'New Task',
            'task[content]' => 'New Task Content'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', " La tâche a été bien été ajoutée.");

    }

    public function testEditTask(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy([ 'id' => 12]);

        // simulate $testUser being logged in
        $client->loginUser($testUser);


        $crawler = $client->request('GET', '/tasks/8/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Edited Task',
            'task[content]' => 'Edited Task Content'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', " La tâche a bien été modifiée.");
    }

    public function testDeleteTask()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy([ 'id' => 13]);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('POST', '/tasks/41/delete');
        $this->assertResponseRedirects();
        $client->followRedirect();
//        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été supprimée.");
    }

    public function testToggleTask()
    {
        $client = static::createClient();
        $task = static::getContainer()->get(TaskRepository::class);
        $client->request('GET', '/tasks/41/toggle');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div.alert-success');
    }

}
