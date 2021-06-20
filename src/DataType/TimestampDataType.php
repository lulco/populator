<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class TimestampDataType extends AbstractDataType
{
    public function populate(Column $column): int
    {
        return $this->faker->unixTime();
    }
}
