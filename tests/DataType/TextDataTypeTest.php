<?php

namespace Populator\Tests\DataType;

use Populator\DataType\TextDataType;
use Populator\Structure\Column;

class TextDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'text');
        $dataType = new TextDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_string($populatedData));
            $this->assertGreaterThanOrEqual(1, strlen($populatedData));
            $this->assertLessThanOrEqual(65535, strlen($populatedData));
        }
    }
}
