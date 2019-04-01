<?php

namespace Populator\Tests\Database;

use Populator\Data\Item;
use Populator\Structure\Column;
use Populator\Structure\Table;

class MultiplePrimaryKeysTableTest extends AbstractDatabaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createMultiplePrimaryKeysTable();
    }

    public function testStructure()
    {
        $table = $this->database->getTableStructure('multiple_primary_keys');
        $this->assertInstanceOf(Table::class, $table);

        $columns = $table->getColumns();
        $this->assertNotEmpty($columns);

        $this->assertEquals(['pk1', 'pk2'], $table->getPrimary());
        $this->assertEmpty($table->getForeignKeys());

        $this->assertArrayHasKey('pk1', $columns);
        /* @var $pk1Column Column */
        $pk1Column = $columns['pk1'];
        $this->assertInstanceOf(Column::class, $pk1Column);
        $this->assertEquals('pk1', $pk1Column->getName());
        $this->assertEquals('integer', $pk1Column->getType());
        $this->assertFalse($pk1Column->isAutoincrement());
        $this->assertFalse($pk1Column->isNullable());
        $this->assertNull($pk1Column->getDefault());
        $this->assertEquals(11, $pk1Column->getLength());
        $this->assertNull($pk1Column->getDecimals());
        $this->assertFalse($pk1Column->isUnsigned());
        $this->assertNull($pk1Column->getValues());

        $this->assertArrayHasKey('pk2', $columns);
        /* @var $pk2Column Column */
        $pk2Column = $columns['pk2'];
        $this->assertInstanceOf(Column::class, $pk2Column);
        $this->assertEquals('pk2', $pk2Column->getName());
        $this->assertEquals('uuid', $pk2Column->getType());
        $this->assertFalse($pk2Column->isAutoincrement());
        $this->assertFalse($pk2Column->isNullable());
        $this->assertNull($pk2Column->getDefault());
        $this->assertEquals(36, $pk2Column->getLength());
        $this->assertNull($pk2Column->getDecimals());
        $this->assertFalse($pk2Column->isUnsigned());
        $this->assertNull($pk2Column->getValues());

        $this->assertArrayHasKey('title', $columns);
        /* @var $titleColumn Column */
        $titleColumn = $columns['title'];
        $this->assertInstanceOf(Column::class, $titleColumn);
        $this->assertEquals('title', $titleColumn->getName());
        $this->assertEquals('string', $titleColumn->getType());
        $this->assertFalse($titleColumn->isAutoincrement());
        $this->assertFalse($titleColumn->isNullable());
        $this->assertNull($titleColumn->getDefault());
        $this->assertEquals(255, $titleColumn->getLength());
        $this->assertNull($titleColumn->getDecimals());
        $this->assertFalse($titleColumn->isUnsigned());
        $this->assertNull($titleColumn->getValues());

        $this->assertArrayHasKey('is_active', $columns);
        /* @var $activeColumn Column */
        $activeColumn = $columns['is_active'];
        $this->assertInstanceOf(Column::class, $activeColumn);
        $this->assertEquals('is_active', $activeColumn->getName());
        $this->assertEquals('boolean', $activeColumn->getType());
        $this->assertFalse($activeColumn->isAutoincrement());
        $this->assertFalse($activeColumn->isNullable());
        $this->assertEquals(0, $activeColumn->getDefault());
        $this->assertEquals(1, $activeColumn->getLength());
        $this->assertNull($activeColumn->getDecimals());
        $this->assertFalse($activeColumn->isUnsigned());
        $this->assertNull($activeColumn->getValues());
    }

    public function testInsert()
    {
        $item = $this->database->insert('multiple_primary_keys', [
            'pk1' => 100,
            'pk2' => '6a2bbdf6-41f3-4234-8d5b-7e64b604b2c3',
            'title' => 'My title',
        ]);
        $this->assertInstanceOf(Item::class, $item);
        $this->assertEquals(100, $item->getValue('pk1'));
        $this->assertEquals('6a2bbdf6-41f3-4234-8d5b-7e64b604b2c3', $item->getValue('pk2'));
        $this->assertEquals('My title', $item->getValue('title'));
    }
}
