<?php
namespace App\Tests;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\Todolist;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;
use DateTime;
use DateInterval;


class ItemTest extends TestCase {

    private $items;

    protected function setUp(): void
    {
        parent::setUp();


        $this->items = new Item(
            'nom to do',
            'blabla',
            Carbon::now()->subDecades(3)->subMonths(5)->subDays(22),
        );

            }
        public function testIsValidNominal()
    {
        $this->assertTrue($this->items->isValid());
    }

        public function testIsNameEmpty()
    {
        $this->items->setName('');
        $this->assertFalse($this->items->isValid());
    }
    public function testIsNameNotEmpty()
    {
        $this->items->setName('yooooooo');
        $this->assertTrue($this->items->isValid());
    }

        public function testIsContentEmpty()
    {
        $this->items->setContent('');
        $this->assertFalse($this->items->isValid());
    }

    public function testIsContentNotEmpty()
    {
        $this->items->setContent('eeeeeeeeeee');
        $this->assertTrue($this->items->isValid());
    }

        public function testIsLengthContent()
    {
        $this->items->setContent('azz');
        $this->assertTrue($this->items->isValid());
    }

        public function testIsDateEmpty()
    {
        $this->items->setCreatedAt('');
        $this->assertFalse($this->items->isValid());
    }


}