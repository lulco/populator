<?php

namespace Populator\DataType;

use Faker\Generator;
use Populator\Structure\Column;

abstract class AbstractDataType implements DataTypeInterface
{
    protected Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * @return mixed
     */
    abstract public function populate(Column $column);
}
