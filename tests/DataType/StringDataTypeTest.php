<?php

namespace Populator\Tests\Structure;

use Populator\DataType\StringDataType;
use Populator\Structure\Column;

class StringDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'string');
        $dataType = new StringDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertLessThanOrEqual(10, strlen($populatedData));
        }
    }

    public function testLengthSettingsColumn()
    {
        $column = new Column('column', 'string', ['length' => 100]);
        $dataType = new StringDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertLessThanOrEqual(100, strlen($populatedData));
        }
    }
}
