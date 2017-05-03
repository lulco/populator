<?php

namespace Populator\DataType;

use Faker\Generator;
use Populator\Structure\Column;

abstract class AbstractDataType implements DataTypeInterface
{
    /** @var Generator */
    protected $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    abstract public function populate(Column $column): string;
}
