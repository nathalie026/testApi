<?php
namespace App\Tests;

use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;


class UserTest extends TestCase {

    private User $user;

    protected function setUp(): void
    {
        $this->user = new User('Toto', 'Tata', Carbon::now()->subDecades(2), 'toto@yolo.fr', 'azertyuiop' );
        parent::setUp();
    }

    public function testIsValidNominal()
    {
        $this->assertTrue($this->user->isValid());
    }

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
        $this->user->setPassword('ffff');
        $this->assertFalse($this->user->isValid());
    }

}