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

    private $item;

    protected function setUp(): void
    {
        parent::setUp();


        $this->item = new Item(
            'nom to do',
            'blabla',
            Carbon::now()->subDecades(3)->subMonths(5)->subDays(22),
        );

            }
        public function testIsValidNominal()
    {
        $this->assertTrue($this->item->isValid());
    }

        public function testIsNameEmpty()
    {
        $this->item->setName('');
        $this->assertFalse($this->item->isValid());
    }
    public function testIsNameNotEmpty()
    {
        $this->item->setName('test');
        $this->assertTrue($this->item->isValid());
    }

    public function testIsContentNotEmpty()
    {
        $this->item->setContent('test');
        $this->assertTrue($this->item->isValid());
    }

        public function testIsContentEmpty()
    {
        $this->item->setContent('');
        $this->assertFalse($this->item->isValid());
    }


        public function testIsLengthContent()
    {
        $this->item->setContent('azz');
        $this->assertTrue($this->item->isValid());
    }

        public function testIsDateEmpty()
    {
        $this->item->setCreatedAt('');
        $this->assertFalse($this->item->isValid());
    }


}