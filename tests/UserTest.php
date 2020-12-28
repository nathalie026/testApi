<?php
namespace App\Tests;

use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;


class UserTest extends TestCase {

    private User $user;

    protected function setUp(): void
    {
        $this->user = new User('Tata', 'Tata', 13, 'toto@yolo.fr', 'azertyuiop' );
        parent::setUp();
    }

    public function testIsValidNominal()
    {
        $this->assertTrue($this->user->isValid());
    }

    public function testIsNotValidDueToEmptyFirstnameAndReturnException()
    {
        $this->user->setFirstname('');
        $exception = $this->user->isValid();
        $this->assertEquals('Le prénom est vide', $exception->getMessage());
    }

    public function testIsNotValidDueToEmptyLastnameAndReturnException()
    {
        $this->user->setLastname('');
        $exception = $this->user->isValid();
        $this->assertEquals('Le nom est vide', $exception->getMessage());
    }

    public function testIsNotValidDueToEmptyEmailAndReturnException()
    {
        $this->user->setEmail('');
        $exception = $this->user->isValid();
        $this->assertEquals('L\'email est vide', $exception->getMessage());
    }

    public function testIsNotValidDueToBadEmailAndReturnException()
    {
        $this->user->setEmail('pasbon');
        $exception = $this->user->isValid();
        $this->assertEquals('Format d\'email non valide', $exception->getMessage());
    }

    public function testIsNotValidDueToBirthdayToYoungAndReturnException()
    {
        $this->user->setBirthday(8);
        $exception = $this->user->isValid();
        $this->assertEquals('L\'utilisateur doit  être agé de 13 ans au minimum', $exception->getMessage());
    }


    public function testIsNotValidDueToEmptyPasswordAndReturnException()
    {
        $this->user->setPassword('');
        $exception = $this->user->isValid();
        $this->assertEquals('Le mot de passe est vide', $exception->getMessage());
    }

    public function testIsNotValidDueToLengthPasswordAndReturnException()
    {
        $this->user->setPassword('eded');
        $exception = $this->user->isValid();
        $this->assertEquals('Le mot de passe doit faire entre 8 et 40 caractères', $exception->getMessage());
    }

    public function testIsValidPassword()
    {
        $this->user->setPassword('ededeazer');
        $this->assertTrue($this->user->isValid());
    }

    public function testIsValidDueToFirstname()
    {
        $this->user->setFirstname('Joe');
        $this->assertTrue($this->user->isValid());
    }

    public function testIsValidDueToLastname()
    {
        $this->user->setLastname('Biden');
        $this->assertTrue($this->user->isValid());
    }

    public function testIsValidDueToEmail()
    {
        $this->user->setEmail('joebien@joe.fr');
        $this->assertTrue($this->user->isValid());
    }


}