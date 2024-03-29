<?php

namespace Populator\Tests\DataType;

use Populator\DataType\TimeDataType;
use Populator\Structure\Column;

class TimeDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn(): void
    {
        $column = new Column('column', 'time');
        $dataType = new TimeDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_string($populatedData));
            $this->assertEquals(8, strlen($populatedData));
            $this->assertMatchesRegularExpression('/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $populatedData);
        }
    }
}
