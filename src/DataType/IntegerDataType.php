<?php

namespace Populator\DataType;

use Populator\Structure\Column;

/**
 * https://dev.mysql.com/doc/refman/5.7/en/integer-types.html
 */
class IntegerDataType extends AbstractDataType
{
    protected int $bytes = 4;

    public function populate(Column $column): int
    {
        $total = pow(256, $this->bytes);
        $min = $column->isUnsigned() ? 0 : (-1) * $total / 2;
        $min = max(PHP_INT_MIN, $min);
        $max = $column->isUnsigned() ? $total - 1 : $total / 2 - 1;
        $max = min(PHP_INT_MAX, $max);
        return $this->faker->numberBetween($min, $max);
    }
}
