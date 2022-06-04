<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserEntityTest extends KernelTestCase
{

    private const INVALID_EMAIL_VALUE = "joe.doe@gmail";
    private const VALID_EMAIL_VALUE = "joe.doe@gmail.com";
    private User $user;
    private Task $task;

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get("validator")->validate($user);

        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function setUp(): void
    {
        $this->user = new User();
        $this->task = new Task();
    }

    public function testUsername()
    {
        $this->user->setUsername('Joe');
        $this->assertSame('Joe', $this->user->getUsername());
    }

    public function testPassword()
    {
        $this->user->setPassword('password');
        $this->assertSame('password', $this->user->getPassword());
    }

    public function testEmail()
    {
        $this->user->setEmail('user@domain.com');
        $this->assertSame('user@domain.com', $this->user->getEmail());
    }

    public function testRole()
    {
        $this->user->setRoles(['ROLE_USER']);
        $this->assertSame(['ROLE_USER'], $this->user->getRoles());
    }

    public function testEmailIsValid()
    {
        $this->user
            ->setEmail(self::VALID_EMAIL_VALUE);
        $this->assertHasErrors($this->user);
    }

    public function testEmailIsNotValid(): void
    {
        $this->user
            ->setEmail(self::INVALID_EMAIL_VALUE);
        $this->assertHasErrors($this->user, 1);
    }

    public function testInvalidBlankEmail()
    {
        $this->user
            ->setEmail('');
        $this->assertHasErrors($this->user, 1);
    }

    public function testTasks()
    {
        $this->user->addTask($this->task);
        $this->assertCount(1, $this->user->getTasks());

        $this->user->removeTask($this->task);
        $this->assertCount(0, $this->user->getTasks());
    }


}
