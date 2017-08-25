<?php

namespace Populator\Tests\Database;

use Populator\Data\Item;
use Populator\Structure\Table;

class NoPrimaryKeysTableTest extends AbstractDatabaseTest
{
    public function testInsert()
    {
        $this->createNoPrimaryKeysTable();
        $this->assertInstanceOf(Table::class, $this->database->getTableStructure('no_primary_key'));

        $item = $this->database->insert('no_primary_key', [
            'meta_key' => 'my_key',
            'meta_value' => 'my_value',
        ]);
        $this->assertInstanceOf(Item::class, $item);
        $this->assertEquals('my_key', $item->getValue('meta_key'));
        $this->assertEquals('my_value', $item->getValue('meta_value'));
    }

    public function testGetRandomRecord()
    {
        $this->createNoPrimaryKeysTable();
        $this->assertInstanceOf(Table::class, $this->database->getTableStructure('no_primary_key'));

        $this->assertNull($this->database->getRandomRecord('no_primary_key'));

        $this->database->insert('no_primary_key', [
            'meta_key' => 'my_key',
            'meta_value' => 'my_value',
        ]);

        $onlyOneRecord = $this->database->getRandomRecord('no_primary_key');

        $this->assertInstanceOf(Item::class, $onlyOneRecord);
        $this->assertEquals('my_key', $onlyOneRecord->getValue('meta_key'));
        $this->assertEquals('my_value', $onlyOneRecord->getValue('meta_value'));

        $this->database->insert('no_primary_key', [
            'meta_key' => 'my_key_2',
            'meta_value' => 'my_value_2',
        ]);

        for ($i = 0; $i < 5; ++$i) {
            $oneOfTwoRecords = $this->database->getRandomRecord('no_primary_key');
            $this->assertTrue(in_array($oneOfTwoRecords->getValue('meta_key'), ['my_key', 'my_key_2']));
            $this->assertTrue(in_array($oneOfTwoRecords->getValue('meta_value'), ['my_value', 'my_value_2']));
        }
    }
}
