<?php

namespace Populator\Tests\Structure;

use Populator\DataType\IntegerDataType;
use Populator\Structure\Column;

class IntegerDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'integer');
        $dataType = new IntegerDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertGreaterThanOrEqual(-2147483648, $populatedData);
            $this->assertLessThanOrEqual(2147483647, $populatedData);
        }
    }

    public function testLengthSettingsColumn()
    {
        $column = new Column('column', 'integer', ['length' => 5]);
        $dataType = new IntegerDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertGreaterThanOrEqual(-2147483648, $populatedData);
            $this->assertLessThanOrEqual(2147483647, $populatedData);
        }
    }

    public function testUnsignedSettingsColumn()
    {
        $column = new Column('column', 'integer', ['unsigned' => true]);
        $dataType = new IntegerDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertGreaterThanOrEqual(0, $populatedData);
            $this->assertLessThanOrEqual(4294967295, $populatedData);
        }
    }
}
