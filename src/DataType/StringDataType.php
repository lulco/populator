<?php

namespace Populator\DataType;

use Populator\DataType\AbstractDataType;
use Populator\Structure\Column;

class StringDataType extends AbstractDataType
{
    public function populate(Column $column): string
    {
        return $this->faker->text(mt_rand(min($column->getLength(), 10), $column->getLength()));
    }
}
