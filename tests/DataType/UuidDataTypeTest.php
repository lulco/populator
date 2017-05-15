<?php

namespace Populator\Tests\Structure;

use Populator\DataType\UuidDataType;
use Populator\Structure\Column;

class UuidDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'uuid');
        $dataType = new UuidDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertEquals(36, strlen($populatedData));
            $this->assertRegExp('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/', $populatedData);
        }
    }
}
