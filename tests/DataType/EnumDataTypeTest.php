<?php

namespace Populator\Tests\Structure;

use Populator\DataType\EnumDataType;
use Populator\Structure\Column;

class EnumDataTypeTest extends AbstractDataTypeTest
{
    public function testOneValueColumn()
    {
        $column = new Column('column', 'enum', ['values' => ['a']]);
        $dataType = new EnumDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertEquals('a', $populatedData);
        }
    }

    public function testMoreValuesColumn()
    {
        $values = ['a', 'b', 'c'];
        $column = new Column('column', 'enum', ['values' => $values]);
        $dataType = new EnumDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(in_array($populatedData, $values));
        }
    }
}
