<?php

namespace Populator\Tests\Structure;

use Populator\DataType\SetDataType;
use Populator\Structure\Column;

class SetDataTypeTest extends AbstractDataTypeTest
{
    public function testOneValueColumn()
    {
        $column = new Column('column', 'set', ['values' => ['a']]);
        $dataType = new SetDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertEquals('a', $populatedData);
        }
    }

    public function testMoreValuesColumn()
    {
        $values = ['a', 'b', 'c'];
        $possibleValues = [
            'a',
            'b',
            'c',
            'a,b',
            'a,c',
            'b,a',
            'b,c',
            'c,a',
            'c,b',
            'a,b,c',
            'b,a,c',
            'c,a,b',
            'c,b,a',
        ];
        $column = new Column('column', 'set', ['values' => $values]);
        $dataType = new SetDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(in_array($populatedData, $possibleValues));
        }
    }
}
