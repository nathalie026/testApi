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
        $this->expectException('Exception');
        $this->expectExceptionMessage('Le prénom est vide');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToEmptyLastnameAndReturnException()
    {
        $this->user->setLastname('');
        $this->expectException('Exception');
        $this->expectExceptionMessage('Le nom est vide');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToEmptyEmailAndReturnException()
    {
        $this->user->setEmail('');
        $this->expectException('Exception');
        $this->expectExceptionMessage('L\'email est vide');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToBadEmailAndReturnException()
    {
        $this->user->setEmail('pasbon');
        $this->expectException('Exception');
        $this->expectExceptionMessage('Format d\'email non valide');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToBirthdayToYoungAndReturnException()
    {
        $this->user->setBirthday(8);
        $this->expectException('Exception');
        $this->expectExceptionMessage('L\'utilisateur doit  être agé de 13 ans au minimum');
        $this->assertFalse($this->user->isValid());
    }


    public function testIsNotValidDueToEmptyPasswordAndReturnException()
    {
        $this->user->setPassword('');
        $this->expectException('Exception');
        $this->expectExceptionMessage('Le mot de passe est vide');
        $this->assertFalse($this->user->isValid());

    }

    public function testIsNotValidDueToLengthPasswordAndReturnException()
    {
        $this->user->setPassword('eded');
        $this->expectException('Exception');
        $this->expectExceptionMessage('Le mot de passe doit faire entre 8 et 40 caractères');
        $this->assertFalse($this->user->isValid());
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