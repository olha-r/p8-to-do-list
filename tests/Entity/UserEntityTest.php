<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class UserEntityTest extends KernelTestCase
{
//    private const EMAIL_CONSTRAINT_MESSAGE = "Le format de l'adresse n'est pas correcte.";
//    private const NOT_BLANK_MESSAGE = "Vous devez saisir une adresse email.";
    private const INVALID_EMAIL_VALUE = "joe.doe@gmail";
    private const VALID_EMAIL_VALUE = "joe.doe@gmail.com";
    private $user;
    private $task;

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

    public function testUserEmailIsValid(): void
    {
        $user = new User();
        $user
            ->setEmail(self::VALID_EMAIL_VALUE);
        $validator = Validation::createValidator();
        $errors = $validator->validate($user);
        $this->assertCount(0, $errors);
    }

//    public function testUserEmailIsNotValid(): void
//    {
//        $user = new User();
//        $user
//            ->setEmail(self::INVALID_EMAIL_VALUE);
//        $validator = Validation::createValidator();
//        Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
//        $errors = $validator->validate($user);
//        $this->assertCount(1, $errors);
//    }

    public function testTasks()
    {
        $tasks = $this->user->getTasks();
        $this->assertSame($this->user->getTasks(), $tasks);
    }




}
