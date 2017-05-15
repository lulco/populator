<?php

namespace Populator\DataType;

use Populator\Structure\Column;

interface DataTypeInterface
{
    /**
     * @param Column $column
     * @return mixed
     */
    public function populate(Column $column);
}
