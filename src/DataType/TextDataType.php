<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class TextDataType extends AbstractDataType
{
    protected int $max = 65535;

    public function populate(Column $column): string
    {
        return $this->faker->realTextBetween(10, $this->max);
    }
}
