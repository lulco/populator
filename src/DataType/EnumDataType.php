<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class EnumDataType extends AbstractDataType
{
    public function populate(Column $column): string
    {
        $availableValues = $column->getValues() ?: [];
        return $availableValues[array_rand($availableValues)];
    }
}
