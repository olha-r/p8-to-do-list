<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserEntityTest extends KernelTestCase
{
    private const NOT_BLANK_MESSAGE = "Vous devez saisir une adresse email.";

    private const EMAIL_CONSTRAINT_MESSAGE = "Le format de l'adresse n'est pas correcte.";

    private const INVALID_EMAIL_VALUE = "joe.doe-974@gmail";

    private const VALID_EMAIL_VALUE = "joe.doe-974@gmail.com";
//    private const PASSWORD_REGEX_CONSTRAINT_MESSAGE = "";

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get('validator');
    }

    public function testUserEntityIsValid(): void
    {
       $user = new User();

       $user
           ->setEmail(self::VALID_EMAIL_VALUE);

       $this->getValidationErrors($user, 0);


    }

    private function getValidationErrors(User $user, int $numberOfExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($user);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
