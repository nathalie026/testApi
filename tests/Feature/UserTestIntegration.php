<?php

namespace App\Tests;

use App\Entity\User;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Runner\Exception;

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
            "email" => "toto@yolo.fr",
            "password" => "azertyuiop",
        ];

//        $this->client = static::createClient();
    }

//    // Test d'intégration requête OK pour création suer
//    public function testIsValidNominal()
//    {
//        // GIVEN
//        // on mock un user
//
//
//        //WHEN
//        // création du client
//        // $client = new Client();
//       $client = static::createClient();
//
//        // requête faite par le client avec la méthode POST car création user
//        //$response = $client->request("POST", self::$url . '/createuserok', json_encode($user));
//        $client->request('POST', "/createuserok");
//
//        // THEN
//        // Et donc en retour, ce qu'on doit obtenir de cet appel, c'est le code 201 qui permet de dire que le user a été créé
//        $this->assertEquals(201, $client->getResponse()->getStatusCode());
//    }


    // Test d'intégration requête OK pour création suer
    public function testAddUserOk()
    {
        $this->user = [
            "firstname" => "Tata",
            "lastname" => "Toto",
            "birthday" => 13,
            "email" => "toto@yolo.fr",
            "password" => "azertyuiop",
        ];

        $this->client = static::createClient();
        $this->client->request('POST', '/createuser',$this->user);
        $content = json_decode($this->client->getResponse()->getContent())->title;

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("Controller : response : user create", $content);
    }
}
