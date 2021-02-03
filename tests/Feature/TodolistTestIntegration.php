<?php

namespace App\Tests;

use App\Entity\Todolist;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Runner\Exception;

class TodolistTestIntegration extends WebTestCase


{
    private $user;
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
            'description' => 'hello, this is my description'
        ];

        // mocker un ou des items
    }



    public function testCreateTodolist()
    {
        // GIVEN

        //WHEN
        $this->client->request('POST', '/createuser',$this->user);

        // THEN

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }


    // TEST si user est login
    // TEST si user a déjà une todolist
    // TEST la todolist maximum 10 items
    // TEST si ajout item on todolist is success
    // TEST ajout item trop récent; 30mmn between another add


}
