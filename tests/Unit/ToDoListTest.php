<?php
namespace App\Tests;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\Todolist;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;


class ToDoListTest extends TestCase {

    private User $user;
    private Item $item;
    private \PHPUnit\Framework\MockObject\MockObject $Todolist;


    protected function setUp(): void
    {
        parent::setUp();


        $this->item = new Item(
            'nom to do',
            'description de la todo',
            Carbon::now()->subHour(),
        );

        $this->user = new User(
            'toto',
            'tata',
            14,
            'test@test.com',
            'azertyuioiop',
        );


        $this->Todolist = $this->getMockBuilder(Todolist::class)
        ->onlyMethods(['getSizeTodoItemsCount', 'getLastItem', 'sendEmailToUser'])
        ->getMock();
        $this->Todolist->setUser($this->user);

    }

    public function testCanAddItemNominal()
    {
        $this->Todolist->expects($this->any())->method('getSizeTodoItemsCount')->willReturn(1);
        $this->Todolist->expects($this->any())->method('getLastItem')->willReturn($this->item);


        $canAddItem = $this->Todolist->canAddItem($this->item);

        $this->assertNotNull($canAddItem);
        $this->assertEquals('nom to do', $canAddItem->getName());
    }

    public function testCantAddItemMax(){
        $this->Todolist->expects($this->any())->method('getSizeTodoItemsCount')->willReturn(11);

        $this->expectException('Exception');
        $this->expectExceptionMessage('Todolist is full, cannot includes more than 10 items');

        $canAddItem = $this->Todolist->canAddItem($this->item);
        $this->assertTrue($canAddItem);
    }

    public function testSendEmailToUser()
    {
        $this->Todolist->expects($this->once())->method('getSizeTodoItemsCount')->willReturn('8');

        $send = $this->Todolist->AlertEightItems();

        $this->assertTrue($send);
    }

//    public function testAddItemTooFast()
//    {
//        $this->Todolist->expects($this->any())->method('getLastItem')->willReturn(1);
//
//
//        $this->expectException('Exception');
//        $this->expectExceptionMessage('Last item est trop récent, 30mn entre la création de 2 items');
//
//        $canAddItem = $this->Todolist->canAddItem($this->item);
//        $this->assertFalse($canAddItem);
//    }
}