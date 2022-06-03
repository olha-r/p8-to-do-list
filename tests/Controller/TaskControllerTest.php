<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTasksListPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/');

        $this->assertResponseIsSuccessful();
    }
}
