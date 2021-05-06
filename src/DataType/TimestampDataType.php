<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class TimestampDataType extends AbstractDataType
{
    public function populate(Column $column): string
    {
        return $this->faker->unixTime();
    }
}
