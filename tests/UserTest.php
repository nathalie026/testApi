<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\User;


class UserTest extends TestCase {
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User('test@test.fr', 'toto', 'tata', Carbon::now()->subDecades(2));
        parent::setUp();
    }
     public function testGetFirstName()
     {
         $user = new \App\Entity\User();
         $user->setFirstname('Lea');
         $this->assertEquals($user->getFirstname(), 'Lea');
     }

    public function testNotGetFirstName()
    {
        $user = new \App\Entity\User();
        $user->setFirstname('Lea');
        $this->assertNotEquals($user->getFirstname(), 'Leo');
    }



}