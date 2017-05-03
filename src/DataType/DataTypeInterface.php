<?php

namespace Populator\DataType;

use Populator\Structure\Column;

interface DataTypeInterface
{
    public function populate(Column $column): string;
}
