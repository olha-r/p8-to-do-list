<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function testCount(): void
    {
        $kernel = self::bootKernel();

        $users = static::getContainer()->get(UserRepository::class)->count([]);
//            ->get(UserRepository::class)->count([]);

        $this->assertSame(10, $users);
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
