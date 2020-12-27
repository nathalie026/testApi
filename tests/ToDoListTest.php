<?php
namespace App\Tests;

use App\Entity\User;
use App\Entity\Item;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;


class ToDoListTest extends TestCase {

    private $user;
    private $item;
    private $todoList;


    protected function setUp(): void
    {
        parent::setUp();

        $this->item = new Item([
            'name' => 'nom to do',
            'content' => 'description de la todo',
            'createdAt' => Carbon::now()->subHour()
        ]);

        $this->user = new User([
            'firstname' => 'toto',
            'lastname' => 'tata',
            'email' => 'test@test.com',
            'password' => 'azertyuioiop',
            'birthday' => Carbon::now()->subDecades(3)->subMonths(5)->subDays(22)->toDateString()
        ]);

        $this->todoList = $this->getMockBuilder(Todolist::class)
        ->onlyMethods(['actualItemsCount', 'getLastItem'])
        ->getMock();
        $this->todoList->user = $this->user;
    }

    public function testCanAddItemNominal()
    {
        $this->todoList->expects($this->once())->method('actualItemsCount')->willReturn(1);
        $this->todoList->expects($this->once())->method('getLastItem')->willReturn($this->item);

        $canAddItem = $this->todoList->canAddItem($this->item);
        $this->assertNotNull($canAddItem);
        $this->assertEquals('nom to do', $canAddItem->name);
    }

    public function testCantAddItemMax(){
        $this->todoList->expects($this->any())->method('actualItemsCount')->willReturn(10);

        $this->expectException('Exception');
        $this->expectExceptionMessage('La ToDoList comporte beaucoup trop d items, maximum 10');

        $this->todoList->canAddItem($this->item);
    }

   
}