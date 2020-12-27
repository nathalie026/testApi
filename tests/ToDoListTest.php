<?php
namespace App\Tests;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\Todolist;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;


class ToDoListTest extends TestCase {

    private $user;
    private $items;
    private $Todolist;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User(
            'toto',
            'tata',
            Carbon::now()->subDecades(3)->subMonths(5)->subDays(22),
            'test@test.com',
            'azertyuioiop',
        );

        $this->items = new Item(
            'nom to do',
            'description de la todo',
            Carbon::now()->subHour(),
        );


        $this->Todolist = $this->getMockBuilder(Todolist::class)
        ->onlyMethods(['getSizeTodoItemsCount', 'getLastItem', 'sendEmailToUser'])
        ->getMock();
        $this->Todolist->user = $this->user;
        $this->Todolist->expects($this->any())->method('getLastItem')->willReturn($this->items);
    }

    public function testCanAddItemNominal()
    {
        $this->Todolist->expects($this->any())->method('getSizeTodoItemsCount')->willReturn(1);


        $canAddItem = $this->Todolist->canAddItem($this->items);

        $this->assertNotNull($canAddItem);
        $this->assertEquals('nom to do', $canAddItem->getName());
    }

    public function testCantAddItemMax(){
        $this->Todolist->expects($this->any())->method('getSizeTodoItemsCount')->willReturn(10);

        $this->expectException('Exception');
        $this->expectExceptionMessage('La ToDoList comporte beaucoup trop d items, maximum 10');

        $canAddItem = $this->Todolist->canAddItem($this->items);
        $this->assertTrue($canAddItem);
    }

    public function testSendEmailToUser()
    {
        $this->Todolist->expects($this->once())->method('getSizeTodoItemsCount')->willReturn('8');

        $send = $this->Todolist->AlertEightItems();

        $this->assertTrue($send);
    }
}