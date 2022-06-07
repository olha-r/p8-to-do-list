<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class TaskEntityTest extends KernelTestCase
{

    private Task $task;
    private \DateTime $date;
    private User $user;

    public function setUp() :void
    {
        $this->task = new Task();
        $this->date = new \DateTime();
        $this->user = new User();
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get("validator")->validate($task);

        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
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

    public function testValidEntity(): void
    {
        $this->task
            ->setTitle('Title')
            ->setContent("content");
        $this->assertHasErrors($this->task);
    }

    public function testInvalidBlankTitle(): void
    {
        $this->task
            ->setTitle('')
            ->setContent("content");
        $this->assertHasErrors($this->task, 1);
    }

    public function testInvalidBlankContent()
    {
        $this->task->setTitle("title");
        $this->task->setContent("");
        $this->assertHasErrors($this->task, 1);
    }


}
