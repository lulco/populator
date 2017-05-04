<?php

namespace Populator\Tests\Structure;

use PHPUnit\Framework\TestCase;
use Populator\Structure\Column;
use Populator\Structure\ForeignKey;
use Populator\Structure\Table;

class TableTest extends TestCase
{
    public function testEmptyTable()
    {
        $table = new Table('my_table');
        $this->assertInstanceOf(Table::class, $table);
        $this->assertEquals('my_table', $table->getName());
        $this->assertEmpty($table->getColumns());
        $this->assertEmpty($table->getPrimary());
        $this->assertEmpty($table->getForeignKeys());
    }

    public function testSimpleTable()
    {
        $table = new Table('my_table');
        $this->assertInstanceOf(Table::class, $table);
        $this->assertEquals('my_table', $table->getName());
        $this->assertInstanceOf(Table::class, $table->addColumn(new Column('id', 'integer')));
        $this->assertInstanceOf(Table::class, $table->addColumn(new Column('title', 'string')));
        $this->assertInstanceOf(Table::class, $table->setPrimary(['id']));
        $this->assertCount(2, $table->getColumns());
        $this->assertCount(1, $table->getPrimary());
        $this->assertEquals(['id'], $table->getPrimary());
        $this->assertEmpty($table->getForeignKeys());
    }

    public function testComplexTable()
    {
        $table = new Table('my_table');
        $this->assertInstanceOf(Table::class, $table);
        $this->assertEquals('my_table', $table->getName());
        $this->assertInstanceOf(Table::class, $table->addColumn(new Column('id1', 'integer')));
        $this->assertInstanceOf(Table::class, $table->addColumn(new Column('id2', 'integer')));
        $this->assertInstanceOf(Table::class, $table->addColumn(new Column('title', 'string')));
        $this->assertInstanceOf(Table::class, $table->addColumn(new Column('fk_column', 'integer')));
        $this->assertInstanceOf(Table::class, $table->setPrimary(['id1', 'id2']));
        $this->assertInstanceOf(Table::class, $table->addForeignKey(new ForeignKey(['fk_column'], 'referenced_table')));
        $this->assertCount(4, $table->getColumns());
        foreach ($table->getColumns() as $column) {
            $this->assertInstanceOf(Column::class, $column);
        }
        $this->assertCount(2, $table->getPrimary());
        $this->assertEquals(['id1', 'id2'], $table->getPrimary());
        $this->assertCount(1, $table->getForeignKeys());
        foreach ($table->getForeignKeys() as $foreignKey) {
            $this->assertInstanceOf(ForeignKey::class, $foreignKey);
        }
    }
}
