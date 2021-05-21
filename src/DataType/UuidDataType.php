<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class UuidDataType extends AbstractDataType
{
    public function populate(Column $column): string
    {
        return $this->faker->uuid;
    }
}
