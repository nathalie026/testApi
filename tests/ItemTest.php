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

        $today = new DateTime('now');
        $createdItemAt = $today->add(new DateInterval('PT45M'));

        $this->items = new Item(
            'nom todo',
            'blabla',
            $createdItemAt
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

        public function testIsContentEmpty()
    {
        $this->items->setContent('');
        $this->assertFalse($this->items->isValid());
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