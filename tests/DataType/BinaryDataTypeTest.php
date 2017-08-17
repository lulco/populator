<?php

namespace Populator\Tests\DataType;

use Populator\DataType\BinaryDataType;
use Populator\Structure\Column;

class BinaryDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'binary');
        $dataType = new BinaryDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_string($populatedData));
            $this->assertLessThanOrEqual(10, strlen($populatedData));
        }
    }

    public function testLengthSettingsColumn()
    {
        $column = new Column('column', 'binary', ['length' => 100]);
        $dataType = new BinaryDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_string($populatedData));
            $this->assertLessThanOrEqual(100, strlen($populatedData));
        }
    }
}
