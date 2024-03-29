<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class CharDataType extends AbstractDataType
{
    public function populate(Column $column): string
    {
        return $this->faker->realTextBetween(1, mt_rand(10, (int)max($column->getLength(), 10)));
    }
}
