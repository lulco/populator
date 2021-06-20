<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class SetDataType extends AbstractDataType
{
    public function populate(Column $column): string
    {
        $availableValues = $column->getValues() ?: [];
        $count = mt_rand(1, count($availableValues));
        if ($count == count($availableValues)) {
            return implode(',', $availableValues);
        }
        $values = [];
        for ($i = 0; $i < $count; ++$i) {
            $values[] = $availableValues[array_rand($availableValues)];
        }
        return implode(',', array_unique($values));
    }
}
