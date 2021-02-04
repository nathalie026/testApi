<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Todolist;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Runner\Exception;


class TodolistTestIntegration extends WebTestCase


{
    private $user;
    private $todolist;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = [
            "firstname" => "Tata",
            "lastname" => "Toto",
            "birthday" => 13,
            "email" => "toto@yolo.fr",
            "password" => "azertyuiop",
        ];
        $this->todolist = [
            'name' => "ma Todolist",
            'description' => 'hello, this is my description',
            'user' => $this->user
        ];

        // mocker un ou des items
    }


    /*
    * @test
    * login
    */
    public function testVisitingWhileLoggedIn()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('blu@yolo.fr');

        $client->loginUser($testUser);

        // le user est connecté, on vérifie la présence des éléments de la page où il est redirigé
        $client->request('GET', '/profile');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'User logged successfully');
    }


    /*
    * @test
    * create todolist
    */
    public function testCreateTodolist()
    {
        // GIVEN
        // on mock un user, une todolist et un client
        $this->user = [
            "firstname" => "Taa",
            "lastname" => "Tto",
            "birthday" => 15,
            "email" => "tr@y.fr",
            "password" => "azertyuiop",
        ];
        $this->todolist = [
            "name" => "My todolist",
            "description" => "My description...",
            "user_id" => $this->user,
        ];
        

        $this->client = static::createClient();

        // WHEN
        $this->client->request('POST', '/todolistInte', $this->todolist);

        // THEN
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    /** @test 
    * user already has todolist
    */
    public function userAlreadyHasTodolist()
    {
        // GIVEN
        // on mock un user, une todolist et un client
        $this->user = [
            "firstname" => "Taa",
            "lastname" => "Tto",
            "birthday" => 15,
            "email" => "tr@y.fr",
            "password" => "azertyuiop",
        ];
        $this->todolist = [
            "name" => "My todolist",
            "description" => "My description...",
            "user" => $this->user,
        ];
        

        $this->client = static::createClient();

        //WHEN
        $this->client->request('POST', '/todolist', $this->todolist);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    

    // TEST la todolist maximum 10 items
    // TEST si ajout item on todolist is success
    // TEST ajout item trop récent; 30mmn between another add


}
