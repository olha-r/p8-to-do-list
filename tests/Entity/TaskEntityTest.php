<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class TaskEntityTest extends KernelTestCase
{
    private $task;
    private $date;
    private $user;

    public function setUp() :void
    {
        $this->task = new Task();
        $this->date = new \DateTime();
        $this->user = new User();
    }

    public function testCreatedAt()
    {
        $this->task->setCreatedAt($this->date);
        $this->assertSame($this->date, $this->task->getCreatedAt());
    }

    public function testTitle()
    {
        $this->task->setTitle('Task 1');
        $this->assertSame('Task 1', $this->task->getTitle());
    }

    public function testContent()
    {
        $this->task->setContent('Content');
        $this->assertSame('Content', $this->task->getContent());
    }

    public function testUser()
    {
        $this->task->setUser($this->user);
        $this->assertSame($this->user, $this->task->getUser());
    }

    public function testToggle()
    {
        $this->task->toggle(true);
        $this->assertSame(true, $this->task->isDone());
    }

    public function testIsDoneDefault()
    {
        $flag = $this->task->isDone();
        $this->assertSame(false, $flag);
    }

//    public function testValidNotBlankTitle(): void
//    {
//        $this->task
//            ->setTitle('Title');
//        $validator = Validation::createValidator();
//        $errors = $validator->validate($this->task);
//        $this->assertCount(0, $errors);
//    }

//    public function testInvalidBlankTitle(): void
//    {
//        $this->task
//            ->setTitle('');
//        $validator = Validation::createValidator();
//        $errors = $validator->validate($this->task);
//        $this->assertCount(1, $errors);
//    }

//    public function testValidNotBlankContent(): void
//    {
//        $this->task
//            ->setContent('Content');
//        $validator = Validation::createValidator();
//        $errors = $validator->validate($this->task);
//        $this->assertCount(0, $errors);
//    }
//
//    public function testInvalidBlankContent(): void
//    {
//        $this->task
//            ->setContent('');
//        $validator = Validation::createValidator();
//        $errors = $validator->validate($this->task);
//        $this->assertCount(1, $errors);
//    }
}
