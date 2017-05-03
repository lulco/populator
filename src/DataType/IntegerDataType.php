<?php

namespace Populator\DataType;

use Populator\Structure\Column;

/**
 * https://dev.mysql.com/doc/refman/5.7/en/integer-types.html
 */
class IntegerDataType extends AbstractDataType
{
    protected $bytes = 4;

    public function populate(Column $column): string
    {
        $total = pow(256, $this->bytes);
        $min = $column->isUnsigned() ? 0 : (-1) * $total / 2;
        $max = $column->isUnsigned() ? $total - 1 : $total / 2 - 1;
        return $this->faker->numberBetween(intval($min), intval($max));
    }
}
