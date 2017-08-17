<?php

namespace Populator\Tests\DataType;

use Populator\DataType\TinyintegerDataType;
use Populator\Structure\Column;

class TinyintegerDataTypeTest extends AbstractDataTypeTest
{
    public function testNoSettingsColumn()
    {
        $column = new Column('column', 'tinyinteger');
        $dataType = new TinyintegerDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_int($populatedData));
            $this->assertGreaterThanOrEqual(-128, $populatedData);
            $this->assertLessThanOrEqual(127, $populatedData);
        }
    }

    public function testLengthSettingsColumn()
    {
        $column = new Column('column', 'tinyinteger', ['length' => 5]);
        $dataType = new TinyintegerDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertTrue(is_int($populatedData));
            $this->assertGreaterThanOrEqual(-128, $populatedData);
            $this->assertLessThanOrEqual(127, $populatedData);
        }
    }

    public function testUnsignedSettingsColumn()
    {
        $column = new Column('column', 'tinyinteger', ['unsigned' => true]);
        $dataType = new TinyintegerDataType($this->faker);
        for ($i = 0; $i < 10; ++$i) {
            $populatedData = $dataType->populate($column);
            $this->assertGreaterThanOrEqual(0, $populatedData);
            $this->assertLessThanOrEqual(255, $populatedData);
        }
    }
}
