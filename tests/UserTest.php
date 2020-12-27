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
        $this->user = new User('Tata', 'Tata', Carbon::now()->subDecades(2), 'toto@yolo.fr', 'azertyuiop' );
        parent::setUp();
    }

    public function testIsValidNominal()
    {
        $this->assertTrue($this->user->isValid());
    }

    // public function testIsNotValidNominal()
    // {
    //     $this->user->setFirstname('');
    //     $this->user->setLastname('');

    //     $this->expectException('Exception');
    //     $this->expectExceptionMessage('Champs Incorrect');
    // }

    public function testIsNotValidDueToEmptyFirstname()
    {
        $this->user->setFirstname('');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToEmptyLastname()
    {
        $this->user->setLastname('');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToEmptyEmail()
    {
        $this->user->setEmail('');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToBadEmail()
    {
        $this->user->setEmail('pasbon');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToBirthdayToYoung()
    {
        $this->user->setBirthday(Carbon::now()->subYears(10));
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToBirthdayInFuture()
    {
        $this->user->setBirthday(Carbon::now()->addDecade());
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToEmptyPassword()
    {
        $this->user->setPassword('');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToMinPassword()
    {
        $this->user->setPassword('eded');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidDueToMaxPassword()
    {
        $this->user->setPassword('ededferfrzffrefrefreferfeferfrefferferfrefrefreferfrfreferfrefreffersffffffffffffffffs');
        $this->assertFalse($this->user->isValid());
    }

    public function testIsValidPassword()
    {
        $this->user->setPassword('ededeazer');
        $this->assertTrue($this->user->isValid());
    }
}