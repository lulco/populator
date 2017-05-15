<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class RealDataType extends AbstractDataType
{
    public function populate(Column $column): float
    {
        $max = pow(10, $column->getLength() - $column->getDecimals()) - pow(0.1, $column->getDecimals());
        $min = $column->isUnsigned() ? 0 : (-1) * $max;
        return $this->faker->randomFloat($column->getDecimals(), $min, $max);
    }
}
