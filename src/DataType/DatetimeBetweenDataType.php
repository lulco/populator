<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class DatetimeBetweenDataType extends AbstractDataType
{
    protected $startTime;

    protected $endTime;

    protected $format;

    public function populate(Column $column): string
    {
        return $this->faker->dateTimeBetween($this->startTime, $this->endTime)->format($this->format);
    }
}
