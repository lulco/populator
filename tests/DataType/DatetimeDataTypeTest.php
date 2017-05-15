<?php

namespace Populator\Tests\Structure;

use DateTime;
use Populator\DataType\DatetimeDataType;
use Populator\Structure\Column;

class DatetimeDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'datetime');
        $dataType = new DatetimeDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertEquals(19, strlen($populatedData));
            $this->assertRegExp('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $populatedData);
            $this->assertLessThanOrEqual(new DateTime(), $populatedData);
        }
    }
}
