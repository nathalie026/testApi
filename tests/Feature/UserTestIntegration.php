<?php

namespace App\Tests;

use App\Entity\User;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Runner\Exception;

class UserTestIntegration extends WebTestCase
{

    private static $url = "localhost";

    protected function setUp(): void
    {
        // $this->user = new User('Tata', 'Tata', 13, 'toto@yolo.fr', 'azertyuiop');
        $this->user = [
            "firstname" => "Tata",
            "lastname" => "Toto",
            "age" => 13,
            "email" => "toto@yolo.fr",
            "password" => "azertyuiop",
        ];
        parent::setUp();
    }

    // Test d'intégration requête OK pour création suer
    public function testIsValidNominal()
    {
        // GIVEN
        // on mock un user


        //WHEN
        // création du client
        // $client = new Client();
        $client = static::createClient();

        // requête faite par le client avec la méthode POST car création user
        //$response = $client->request("POST", self::$url . '/createuserok', json_encode($user));
        $client->request('POST', "/createuserok");

        // THEN
        // Et donc en retour, ce qu'on doit obtenir de cet appel, c'est le code 201 qui permet de dire que le user a été créé
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
