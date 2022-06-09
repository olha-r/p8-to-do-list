<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    protected $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $adminPassword = $this->hasher->hashPassword($admin, 'password');
        $admin
            ->setUsername("admin")
            ->setEmail("admin@gmail.com")
            ->setPassword($adminPassword)
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        for ($i = 1; $i < 10; $i++) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'password');
            $user
                ->setUsername("user$i")
                ->setEmail("user$i@domain.fr")
                ->setPassword($password)
                ->setRoles(["ROLE_USER"]);

            $manager->persist($user);
            $this->addReference('user_'. $i, $user);
        }
        $manager->flush();
    }

}