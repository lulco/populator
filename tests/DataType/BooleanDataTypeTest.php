<?php

namespace Populator\Tests\DataType;

use Populator\DataType\BooleanDataType;
use Populator\Structure\Column;

class BooleanDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'boolean');
        $dataType = new BooleanDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_bool($populatedData));
        }
    }
}
