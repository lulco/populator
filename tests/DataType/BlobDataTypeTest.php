<?php

namespace Populator\Tests\DataType;

use Populator\DataType\BlobDataType;
use Populator\Structure\Column;

class BlobDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn(): void
    {
        $column = new Column('column', 'blob');
        $dataType = new BlobDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_string($populatedData));
            $this->assertGreaterThanOrEqual(10, strlen($populatedData));
            $this->assertLessThanOrEqual(65535, strlen($populatedData));
        }
    }
}
