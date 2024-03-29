<?php

namespace Populator\Tests\DataType;

use Populator\DataType\CharDataType;
use Populator\Structure\Column;

class CharDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn(): void
    {
        $column = new Column('column', 'char');
        $dataType = new CharDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertLessThanOrEqual(10, strlen($populatedData));
        }
    }

    public function testLengthSettingsColumn(): void
    {
        $column = new Column('column', 'char', ['length' => 100]);
        $dataType = new CharDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertLessThanOrEqual(100, strlen($populatedData));
        }
    }
}
