<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class TaskFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
        $user = $this->getReference('user_'. rand( 1, 9 ));
            $task = new Task();
            $task
                ->setTitle("Task$i")
                ->setContent("Content de task $i")
                ->setCreatedAt(new \DateTime())
                ->setUser($user);
            $manager->persist($task);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
