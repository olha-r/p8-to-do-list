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
        $testUser = static::getContainer()->get(UserRepository::class)->findAll()[0];
        $client->loginUser($testUser);
//        $userRepository = static::getContainer()->get(UserRepository::class);
//
//        // retrieve the test user
//        $testUser = $userRepository->findOneBy([ 'email' => 'user3@domain.fr']);

//        // simulate $testUser being logged in
//        $client->loginUser($testUser);

        $client->request('GET', '/tasks/');
        $this->assertResponseIsSuccessful();

    }

    public function testDoneTaskListPage()
    {
        $client = static::createClient();
        $testUser = static::getContainer()->get(UserRepository::class)->findOneBy([]);
        $client->loginUser($testUser);

        $task = static::getContainer()->get(TaskRepository::class)->findBy(['isDone' => 'true']);

        $crawler = $client->request('GET', '/tasks/done');
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
//        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
//        $testUser = $userRepository->findOneBy([ 'email' => 'user2@domain.fr']);
        // simulate $testUser being logged in
//        $client->loginUser($testUser);
//        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $testUser = static::getContainer()->get(UserRepository::class)->findAll()[0];
        $client->loginUser($testUser);

        $task = static::getContainer()->get(TaskRepository::class)->findOneBy([]);

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');
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
//        $userRepository = static::getContainer()->get(UserRepository::class);
//
//        // retrieve the test user
//        $testUser = $userRepository->findOneBy([ 'id' => 13]);
//        $client->loginUser($testUser);
        $testUser = static::getContainer()->get(UserRepository::class)->findOneBy([]);
        $client->loginUser($testUser);

        $task = static::getContainer()->get(TaskRepository::class)->findAll()[0];
        $crawler = $client->request('POST', '/tasks/'.$task->getId().'/delete');
        $this->assertResponseRedirects();
        $client->followRedirect();
//        $this->assertSelectorExists('div.alert-success');
        $this->assertSelectorTextContains('div.alert-success', " La tâche a bien été supprimée.");

    }


    public function testToggleTask()
    {
        $client = static::createClient();
        $task = static::getContainer()->get(TaskRepository::class)->findOneBy(['isDone'=> '0']);
        $client->request('GET', '/tasks/'.$task->getId().'/toggle');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div.alert-success');
        $client->request('GET', '/tasks/'.$task->getId().'/toggle');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div.alert-success');
    }


}
