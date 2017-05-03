<?php

namespace Populator\DataType;

use Populator\DataType\AbstractDataType;
use Populator\Structure\Column;

class BlobDataType extends AbstractDataType
{
    protected $min = 0;

    protected $max = 65535;

    public function populate(Column $column): string
    {
        return $this->faker->realText(mt_rand(max($this->min, 10), $this->max));
    }
}
