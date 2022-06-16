<?php

namespace Populator\Tests\Structure;

use PHPUnit\Framework\TestCase;
use Populator\Structure\Column;

class ColumnTest extends TestCase
{
    public function testEmptySettings(): void
    {
        $column = new Column('my_column', 'string');
        $this->assertInstanceOf(Column::class, $column);
        $this->assertEquals('my_column', $column->getName());
        $this->assertEquals('string', $column->getType());
        $this->assertFalse($column->isAutoincrement());
        $this->assertFalse($column->isNullable());
        $this->assertNull($column->getDefault());
        $this->assertNull($column->getLength());
        $this->assertNull($column->getDecimals());
        $this->assertFalse($column->isUnsigned());
        $this->assertNull($column->getValues());
    }

    public function testFullSettings(): void
    {
        $column = new Column('my_string_column', 'string', [
            'nullable' => true,
            'default' => 'default value',
            'length' => 100,
        ]);
        $this->assertInstanceOf(Column::class, $column);
        $this->assertEquals('my_string_column', $column->getName());
        $this->assertEquals('string', $column->getType());
        $this->assertFalse($column->isAutoincrement());
        $this->assertTrue($column->isNullable());
        $this->assertEquals('default value', $column->getDefault());
        $this->assertEquals(100, $column->getLength());
        $this->assertNull($column->getDecimals());
        $this->assertFalse($column->isUnsigned());
        $this->assertNull($column->getValues());

        $column = new Column('my_integer_column', 'integer', [
            'autoincrement' => true,
            'nullable' => false,
            'length' => 10,
            'unsigned' => true,
        ]);
        $this->assertInstanceOf(Column::class, $column);
        $this->assertEquals('my_integer_column', $column->getName());
        $this->assertEquals('integer', $column->getType());
        $this->assertTrue($column->isAutoincrement());
        $this->assertFalse($column->isNullable());
        $this->assertNull($column->getDefault());
        $this->assertEquals(10, $column->getLength());
        $this->assertNull($column->getDecimals());
        $this->assertTrue($column->isUnsigned());
        $this->assertNull($column->getValues());

        $column = new Column('my_enum_column', 'enum', [
            'nullable' => true,
            'default' => 'a',
            'values' => ['a', 'b', 'c'],
        ]);
        $this->assertInstanceOf(Column::class, $column);
        $this->assertEquals('my_enum_column', $column->getName());
        $this->assertEquals('enum', $column->getType());
        $this->assertFalse($column->isAutoincrement());
        $this->assertTrue($column->isNullable());
        $this->assertEquals('a', $column->getDefault());
        $this->assertNull($column->getLength());
        $this->assertNull($column->getDecimals());
        $this->assertFalse($column->isUnsigned());
        $this->assertEquals(['a', 'b', 'c'], $column->getValues());
    }
}
