<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class TextDataType extends AbstractDataType
{
    /** @var int */
    protected $min = 0;

    /** @var int */
    protected $max = 65535;

    public function populate(Column $column): string
    {
        return $this->faker->realTextBetween(10, mt_rand((int)max($this->min, 10), $this->max));
    }
}
