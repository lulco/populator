<?php

namespace Populator\Tests\Database;

use PHPUnit\Framework\TestCase;
use Populator\Data\Item;
use Populator\Database\Database;
use Populator\Exception\DatabaseConnectionException;
use Populator\Exception\TableNotFoundException;
use Populator\Structure\Column;
use Populator\Structure\Table;
use Populator\Tests\Behavior\CreateStructureBehavior;

class DatabaseTest extends TestCase
{
    use CreateStructureBehavior;

    private $database;

    protected function setUp()
    {
        $this->cleanup();
        $dsn = 'mysql:host=' . getenv('POPULATOR_MYSQL_HOST') . ';dbname=' . getenv('POPULATOR_MYSQL_DATABASE');
        $this->database = new Database(getenv('POPULATOR_MYSQL_DATABASE'), $dsn, getenv('POPULATOR_MYSQL_USERNAME'), getenv('POPULATOR_MYSQL_PASSWORD'));
    }

    public function testWrongCredentials()
    {
        $dsn = 'mysql:host=' . getenv('POPULATOR_MYSQL_HOST') . ';dbname=' . getenv('POPULATOR_MYSQL_DATABASE');
        $this->expectException(DatabaseConnectionException::class);
        $this->expectExceptionMessage("SQLSTATE[HY000] [1045] Access denied for user '" . getenv('POPULATOR_MYSQL_USERNAME') . "'@'" . getenv('POPULATOR_MYSQL_HOST') . "' (using password: YES)");
        new Database(getenv('POPULATOR_MYSQL_DATABASE'), $dsn, getenv('POPULATOR_MYSQL_USERNAME'), 'wrong_password');
    }

    public function testGetName()
    {
        $this->assertEquals(getenv('POPULATOR_MYSQL_DATABASE'), $this->database->getName());
    }

    public function testGetTableStructureForNonExistingTable()
    {
        $this->expectException(TableNotFoundException::class);
        $this->expectExceptionMessage("Table 'non_existing_table' does not exist.");
        $this->database->getTableStructure('non_existing_table');
    }

    public function testGetTableStructureSimple()
    {
        $this->createSimpleTable();

        $table = $this->database->getTableStructure('simple');
        $this->assertInstanceOf(Table::class, $table);
        $this->assertEquals('simple', $table->getName());
        $columns = $table->getColumns();

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

    public function testInsertSimple()
    {
        $this->createSimpleTable();
        $this->assertInstanceOf(Table::class, $this->database->getTableStructure('simple'));

        $item1 = $this->database->insert('simple', [
            'title' => 'My title 1',
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

    public function testInsertNoPrimaryKeys()
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

    public function testInsertMultiplePrimaryKeys()
    {
        $this->createMultiplePrimaryKeysTable();
        $this->assertInstanceOf(Table::class, $this->database->getTableStructure('multiple_primary_keys'));

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

    public function testGetRandomRecordFromSimpleTable()
    {
        $this->createSimpleTable();
        $this->assertInstanceOf(Table::class, $this->database->getTableStructure('simple'));

        $this->assertNull($this->database->getRandomRecord('simple'));

        $this->database->insert('simple', [
            'title' => 'My title 1',
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

    public function testGetRandomRecordFromNoPrimaryKeysTable()
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
