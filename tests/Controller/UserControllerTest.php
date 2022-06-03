<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUsersListPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users/');

        $this->assertResponseIsSuccessful();
    }
}
