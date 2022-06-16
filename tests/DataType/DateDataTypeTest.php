<?php

namespace Populator\Tests\DataType;

use DateTime;
use Populator\DataType\DateDataType;
use Populator\Structure\Column;

class DateDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn(): void
    {
        $column = new Column('column', 'date');
        $dataType = new DateDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertEquals(10, strlen($populatedData));
            $this->assertMatchesRegularExpression('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $populatedData);
            $this->assertLessThanOrEqual(new DateTime(), $populatedData);
        }
    }
}
