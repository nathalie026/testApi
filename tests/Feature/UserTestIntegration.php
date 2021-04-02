<?php

namespace App\Tests;

use App\Entity\User;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Runner\Exception;
use App\Repository\UserRepository;


class UserTestIntegration extends WebTestCase


{
    private $user;
    private $client;
//    private static $url = "localhost";


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = [
            "firstname" => "Tata",
            "lastname" => "Toto",
            "birthday" => 13,
            "email" => "sasou@yolo.fr",
            "password" => "azertyuiop",
        ];

//        $this->client = static::createClient();
    }


    // Test d'intégration requête OK pour création suer
    public function testAddUserOk()
    {
        // GIVEN
       // on mock un user et un client
        $this->user = [
            "firstname" => "Taa",
            "lastname" => "Tto",
            "birthday" => 15,
            "email" => "traauc@yolo.fr",
            "password" => "azertyuiop",
        ];
        

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
// Et donc en retour, ce qu'on doit obtenir de cet appel, c'est le code 201 qui permet de dire que le user a été créé

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function testVisitingWhileLoggedIn()
    {
        $client = static::createClient();

        // get or create the user somehow (e.g. creating some users only
        // for tests while loading the test fixtures)
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('blu@yolo.fr');

        $client->loginUser($testUser);

        // user is now logged in, so you can test protected resources
        $client->request('GET', '/profile');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'User logged successfully');
    }

    public function testUserSoYoung()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "Tata",
            "lastname" => "Toto",
            "birthday" => 12,
            "email" => "meme@yolo.fr",
            "password" => "azertyuiop",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testPwdTooShort()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "Tata",
            "lastname" => "Toto",
            "birthday" => 10,
            "email" => "meme@yolo.fr",
            "password" => "azer",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testPwdTooLong()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "Tata",
            "lastname" => "Toto",
            "birthday" => 10,
            "email" => "meme@yolo.fr",
            "password" => "azedddddegfyefgyegfyegfyegfyegfyegfyegfyegfyegyfgeygfyegfyegrtyuiop",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testEmailExist()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "Tata",
            "lastname" => "Toto",
            "birthday" => 15,
            "email" => "sszszszsz@yolo.fr",
            "password" => "azertyuiop",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testEmptyFirstname()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "",
            "lastname" => "Toto",
            "birthday" => 14,
            "email" => "meme@yolo.fr",
            "password" => "azertyuiop",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testEmptyLastname()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "Yo",
            "lastname" => "",
            "birthday" => 14,
            "email" => "meme@yolo.fr",
            "password" => "azertyuiop",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testEmptyBirthday()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "zddzz",
            "lastname" => "Toto",
            "birthday" => '',
            "email" => "meme@yolo.fr",
            "password" => "azertyuiop",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testEmptyEmail()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "",
            "lastname" => "Toto",
            "birthday" => 120,
            "email" => "",
            "password" => "azertyuiop",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function testEmptyPwd()
    {
        // GIVEN
        // on mock un user et un client
        $this->user = [
            "firstname" => "ded",
            "lastname" => "Toto",
            "birthday" => 130,
            "email" => "meme@yolo.fr",
            "password" => "",
        ];

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

}
