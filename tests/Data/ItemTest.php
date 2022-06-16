<?php

namespace Populator\Tests\Data;

use DateTime;
use PHPUnit\Framework\TestCase;
use Populator\Data\Item;

class ItemTest extends TestCase
{
    public function testGetValue(): void
    {
        $data = [
            'id' => 'my-id',
            'title' => 'My title',
            'created_at' => new DateTime(),
        ];
        $item = new Item($data);
        $this->assertEquals('my-id', $item->getValue('id'));
        $this->assertEquals('My title', $item->getValue('title'));
        $this->assertInstanceOf(DateTime::class, $item->getValue('created_at'));
        $this->assertNull($item->getValue('undefined_column'));
    }
}
