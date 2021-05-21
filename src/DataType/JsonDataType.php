<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class JsonDataType extends AbstractDataType
{
    public function populate(Column $column): string
    {
        return json_encode([]);
    }
}
