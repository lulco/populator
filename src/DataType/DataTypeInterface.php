<?php

namespace Populator\DataType;

use Populator\Structure\Column;

interface DataTypeInterface
{
    /**
     * @return mixed
     */
    public function populate(Column $column);
}
