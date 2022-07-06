<?php

namespace App\Tests\Security\Voter;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskVoterTest extends TestCase
{
    protected function createAdmin(int $id)
    {
       $admin = $this->createMock(User::class);
       $admin->method('getId')->willReturn($id);
       $admin->method('getRoles')->willReturn(['ROLE_ADMIN']);

       return $admin;
    }

    public function provideCases()
    {
        yield 'N\'est pas de type Task' => [
            'edit', 'delete',
            null,
            $this->createAdmin()
        ];
    }
}