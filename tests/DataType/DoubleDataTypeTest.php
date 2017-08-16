<?php

namespace Populator\Tests\DataType;

use Populator\DataType\DoubleDataType;
use Populator\Structure\Column;

class DoubleDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'double');
        $dataType = new DoubleDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_double($populatedData));
            $this->assertEquals(0, $populatedData);
        }
    }

    public function testLengthSettingsColumn()
    {
        $column = new Column('column', 'double', ['length' => 5]);
        $dataType = new DoubleDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_double($populatedData));
            $this->assertGreaterThanOrEqual(-99999, $populatedData);
            $this->assertLessThanOrEqual(99999, $populatedData);
        }
    }

    public function testLengthAndDecimalsSettingsColumn()
    {
        $column = new Column('column', 'double', ['length' => 7, 'decimals' => 2]);
        $dataType = new DoubleDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_double($populatedData));
            $this->assertGreaterThanOrEqual(-99999.99, $populatedData);
            $this->assertLessThanOrEqual(99999.99, $populatedData);
        }
    }

    public function testUnsignedSettingsColumn()
    {
        $column = new Column('column', 'double', ['length' => 7, 'decimals' => 2, 'unsigned' => true]);
        $dataType = new DoubleDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_double($populatedData));
            $this->assertGreaterThanOrEqual(0, $populatedData);
            $this->assertLessThanOrEqual(99999.99, $populatedData);
        }
    }
}
