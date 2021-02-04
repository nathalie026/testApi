<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\Todolist;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Runner\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodolistTestIntegration extends WebTestCase

{
    private $user;
    private $todolist;
    private $client;
    private $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        // on cree un utilisateur et se log avec
        $this->user = new User();
        $this->user->setFirstname("Titi");
        $this->user->setLastname("Toto");
        $this->user->setBirthday(15);
        $this->user->setEmail("meme@yolo.fr");
        $this->user->setPassword("azertyuiop");
        $this->em->persist($this->user);
        $this->em->flush();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('meme@yolo.fr');

        $this->client->loginUser($testUser);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }

    /*
    * @test
    * login
    */
    public function testVisitingWhileLoggedIn()
    {
        // le user est connecté, on vérifie la présence des éléments de la page où il est redirigé
        $this->client->request('GET', '/profile');
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
        $this->todolist = [
            "name" => "My todolist",
            "description" => "My description...",
        ];
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

        $todolist = [
            "name" => "My todolist",
            "description" => "My description...",
        ];
        // on cree un todolist pour l'user
        $this->client->request('POST', '/todolistInte', $todolist);
        // on essaie de creer un autre todolist pour l'user
        $todolist2 = [];
        $this->client->request('POST', '/todolistInte', $todolist2);

        // THEN
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('"ERROR : User already has a todolist..."', $this->client->getResponse()->getContent());
    }

    // TEST la todolist maximum 10 items

    public function testAddTodolist10Items()
    {
        // GIVEN

        // on cree la todolist pour l'user
        $todolist = new Todolist();
        $todolist->setName("todolistName");
        $todolist->setDescription("todolistDesc");
        $todolist->setUser($this->user);
        $this->user->setTodolist($todolist);
        $this->em->persist($todolist);
        $this->em->persist($this->user);
        $this->em->flush();

        // on cree 10 items dans la todolist
        for ($i = 1; $i <= 10; $i++) {
            $item = new Item("name" . $i, "content" . $i, Carbon::now()->subDays($i));
            $todolist->getItem()->add($item);
            $this->em->persist($item);
            $this->em->persist($todolist);
            $this->em->flush();
        }
        //WHEN
        $item11 = [
            "name" => "name11",
            "content" => "content11"
        ];
        $this->client->request('POST', '/todolist/additem', $item11);

        // THEN
        $this->assertEquals('"ERROR : Todolist is full, cannot includes more than 10 items"', $this->client->getResponse()->getContent());
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    // TEST si ajout item on todolist is success
    public function testAddItemOk()
    {
        // GIVEN

        // on cree la todolist pour l'user
        $todolist = new Todolist();
        $todolist->setName("todolistName");
        $todolist->setDescription("todolistDesc");
        $todolist->setUser($this->user);
        $this->user->setTodolist($todolist);
        $this->em->persist($todolist);
        $this->em->persist($this->user);
        $this->em->flush();

        // on cree 1 item dans la todolist
        //WHEN
        $item1 = [
            "name" => "name1",
            "content" => "content1"
        ];
        $this->client->request('POST', '/todolist/additem', $item1);

        // THEN
        $this->assertEquals('"SUCESS : item name1 content1 added"', $this->client->getResponse()->getContent());
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    // TEST ajout item trop récent; 30mmn between another add
    public function testAddTwoItemsTooSoon()
    {
        // GIVEN

        // on cree la todolist pour l'user
        $todolist = new Todolist();
        $todolist->setName("todolistName");
        $todolist->setDescription("todolistDesc");
        $todolist->setUser($this->user);
        $this->user->setTodolist($todolist);
        $this->em->persist($todolist);
        $this->em->persist($this->user);
        $this->em->flush();

        // on cree 1 item dans la todolist
        //WHEN
        $item1 = [
            "name" => "name1",
            "content" => "content1"
        ];
        $this->client->request('POST', '/todolist/additem', $item1);
        // on verifie que l'ajout s'est bien faite
        $this->assertEquals('"SUCESS : item name1 content1 added"', $this->client->getResponse()->getContent());
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        // on cree un deuxieme item dans la todolist
        $item2 = [
            "name" => "name2",
            "content" => "content2"
        ];
        $this->client->request('POST', '/todolist/additem', $item2);
        $this->assertStringContainsString('"ERROR : Last item is too recent, 30 mins is needed between item creation"', $this->client->getResponse()->getContent());
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }
}
