<?php

namespace Populator\Tests\DataType;

use Populator\DataType\FloatDataType;
use Populator\Structure\Column;

class FloatDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn(): void
    {
        $column = new Column('column', 'float');
        $dataType = new FloatDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_float($populatedData));
            $this->assertEquals(0, $populatedData);
        }
    }

    public function testLengthSettingsColumn(): void
    {
        $column = new Column('column', 'float', ['length' => 5]);
        $dataType = new FloatDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_float($populatedData));
            $this->assertGreaterThanOrEqual(-99999, $populatedData);
            $this->assertLessThanOrEqual(99999, $populatedData);
        }
    }

    public function testLengthAndDecimalsSettingsColumn(): void
    {
        $column = new Column('column', 'float', ['length' => 7, 'decimals' => 2]);
        $dataType = new FloatDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_float($populatedData));
            $this->assertGreaterThanOrEqual(-99999.99, $populatedData);
            $this->assertLessThanOrEqual(99999.99, $populatedData);
        }
    }

    public function testUnsignedSettingsColumn(): void
    {
        $column = new Column('column', 'float', ['length' => 7, 'decimals' => 2, 'unsigned' => true]);
        $dataType = new FloatDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_float($populatedData));
            $this->assertGreaterThanOrEqual(0, $populatedData);
            $this->assertLessThanOrEqual(99999.99, $populatedData);
        }
    }
}
