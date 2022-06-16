<?php

namespace Populator\Tests\DataType;

use Populator\DataType\UuidDataType;
use Populator\Structure\Column;

class UuidDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn(): void
    {
        $column = new Column('column', 'uuid');
        $dataType = new UuidDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_string($populatedData));
            $this->assertEquals(36, strlen($populatedData));
            $this->assertMatchesRegularExpression('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/', $populatedData);
        }
    }
}
