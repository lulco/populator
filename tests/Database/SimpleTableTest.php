<?php

namespace Populator\Tests\Database;

use DateTime;
use Populator\Data\Item;
use Populator\Structure\Column;
use Populator\Structure\Table;

class SimpleTableTest extends AbstractDatabaseTest
{
    public function testGetTableStructure()
    {
        $this->createSimpleTable();

        $table = $this->database->getTableStructure('simple');
        $this->assertInstanceOf(Table::class, $table);
        $this->assertEquals('simple', $table->getName());
        $columns = $table->getColumns();

        $this->assertEquals(['id'], $table->getPrimary());
        $this->assertEmpty($table->getForeignKeys());

        $this->assertNotEmpty($columns);

        $this->assertArrayHasKey('id', $columns);
        /* @var $idColumn Column */
        $idColumn = $columns['id'];
        $this->assertInstanceOf(Column::class, $idColumn);
        $this->assertEquals('id', $idColumn->getName());
        $this->assertEquals('integer', $idColumn->getType());
        $this->assertTrue($idColumn->isAutoincrement());
        $this->assertFalse($idColumn->isNullable());
        $this->assertNull($idColumn->getDefault());
        $this->assertEquals(11, $idColumn->getLength());
        $this->assertNull($idColumn->getDecimals());
        $this->assertFalse($idColumn->isUnsigned());
        $this->assertNull($idColumn->getValues());

        $this->assertArrayHasKey('created_at', $columns);
        /* @var $createdAtColumn Column */
        $createdAtColumn = $columns['created_at'];
        $this->assertInstanceOf(Column::class, $createdAtColumn);
        $this->assertEquals('created_at', $createdAtColumn->getName());
        $this->assertEquals('datetime', $createdAtColumn->getType());
        $this->assertFalse($createdAtColumn->isAutoincrement());
        $this->assertFalse($createdAtColumn->isNullable());
        $this->assertNull($createdAtColumn->getDefault());
        $this->assertNull($createdAtColumn->getLength());
        $this->assertNull($createdAtColumn->getDecimals());
        $this->assertFalse($createdAtColumn->isUnsigned());
        $this->assertNull($createdAtColumn->getValues());

        $this->assertArrayHasKey('title', $columns);
        /* @var $titleColumn Column */
        $titleColumn = $columns['title'];
        $this->assertInstanceOf(Column::class, $titleColumn);
        $this->assertEquals('title', $titleColumn->getName());
        $this->assertEquals('string', $titleColumn->getType());
        $this->assertFalse($titleColumn->isAutoincrement());
        $this->assertFalse($titleColumn->isNullable());
        $this->assertEquals('', $titleColumn->getDefault());
        $this->assertEquals(255, $titleColumn->getLength());
        $this->assertNull($titleColumn->getDecimals());
        $this->assertFalse($titleColumn->isUnsigned());
        $this->assertNull($titleColumn->getValues());

        $this->assertArrayHasKey('type', $columns);
        /* @var $typeColumn Column */
        $typeColumn = $columns['type'];
        $this->assertInstanceOf(Column::class, $typeColumn);
        $this->assertEquals('type', $typeColumn->getName());
        $this->assertEquals('enum', $typeColumn->getType());
        $this->assertFalse($typeColumn->isAutoincrement());
        $this->assertTrue($typeColumn->isNullable());
        $this->assertEquals('type1', $typeColumn->getDefault());
        $this->assertNull($typeColumn->getLength());
        $this->assertNull($typeColumn->getDecimals());
        $this->assertFalse($typeColumn->isUnsigned());
        $this->assertEquals(['type1', 'type2', 'type3'], $typeColumn->getValues());

        $this->assertArrayHasKey('sorting', $columns);
        /* @var $sortingColumn Column */
        $sortingColumn = $columns['sorting'];
        $this->assertInstanceOf(Column::class, $sortingColumn);
        $this->assertEquals('sorting', $sortingColumn->getName());
        $this->assertEquals('integer', $sortingColumn->getType());
        $this->assertFalse($sortingColumn->isAutoincrement());
        $this->assertTrue($sortingColumn->isNullable());
        $this->assertNull($sortingColumn->getDefault());
        $this->assertEquals(10, $sortingColumn->getLength());
        $this->assertNull($sortingColumn->getDecimals());
        $this->assertTrue($sortingColumn->isUnsigned());
        $this->assertNull($sortingColumn->getValues());

        $this->assertArrayHasKey('price', $columns);
        /* @var $priceColumn Column */
        $priceColumn = $columns['price'];
        $this->assertInstanceOf(Column::class, $priceColumn);
        $this->assertEquals('price', $priceColumn->getName());
        $this->assertEquals('double', $priceColumn->getType());
        $this->assertFalse($priceColumn->isAutoincrement());
        $this->assertFalse($priceColumn->isNullable());
        $this->assertNull($priceColumn->getDefault());
        $this->assertEquals(10, $priceColumn->getLength());
        $this->assertEquals(2, $priceColumn->getDecimals());
        $this->assertTrue($priceColumn->isUnsigned());
        $this->assertNull($priceColumn->getValues());
    }

    public function testInsert()
    {
        $this->createSimpleTable();
        $this->assertInstanceOf(Table::class, $this->database->getTableStructure('simple'));

        $item1 = $this->database->insert('simple', [
            'title' => 'My title 1',
            'created_at' => new DateTime(),
            'sorting' => 1000,
            'price' => 123.45,
        ]);
        $this->assertInstanceOf(Item::class, $item1);
        $this->assertEquals(1, $item1->getValue('id'));
        $this->assertEquals('My title 1', $item1->getValue('title'));
        $this->assertEquals('type1', $item1->getValue('type'));
        $this->assertEquals(1000, $item1->getValue('sorting'));
        $this->assertEquals(123.45, $item1->getValue('price'));

        $item2 = $this->database->insert('simple', [
            'title' => 'My title 1',
            'created_at' => new DateTime(),
            'type' => 'type3',
            'sorting' => 2000,
            'price' => 234.56,
        ]);
        $this->assertInstanceOf(Item::class, $item2);
        $this->assertEquals(2, $item2->getValue('id'));
        $this->assertEquals('My title 1', $item2->getValue('title'));
        $this->assertEquals(2000, $item2->getValue('sorting'));
        $this->assertEquals(234.56, $item2->getValue('price'));
    }

    public function testGetRandomRecord()
    {
        $this->createSimpleTable();
        $this->assertInstanceOf(Table::class, $this->database->getTableStructure('simple'));

        $this->assertNull($this->database->getRandomRecord('simple'));

        $this->database->insert('simple', [
            'title' => 'My title 1',
            'created_at' => new DateTime(),
            'sorting' => 1000,
            'price' => 123.45,
        ]);

        $onlyOneRecord = $this->database->getRandomRecord('simple');
        $this->assertInstanceOf(Item::class, $onlyOneRecord);
        $this->assertEquals(1, $onlyOneRecord->getValue('id'));
        $this->assertEquals('My title 1', $onlyOneRecord->getValue('title'));
        $this->assertEquals(1000, $onlyOneRecord->getValue('sorting'));
        $this->assertEquals(123.45, $onlyOneRecord->getValue('price'));

        $this->database->insert('simple', [
            'title' => 'My title 2',
            'created_at' => new DateTime(),
            'sorting' => 2000,
            'price' => 234.56,
        ]);

        for ($i = 0; $i < 5; ++$i) {
            $oneOfTwoRecords = $this->database->getRandomRecord('simple');
            $this->assertTrue(in_array($oneOfTwoRecords->getValue('id'), [1, 2]));
            $this->assertTrue(in_array($oneOfTwoRecords->getValue('title'), ['My title 1', 'My title 2']));
            $this->assertTrue(in_array($oneOfTwoRecords->getValue('sorting'), [1000, 2000]));
        }
    }
}
