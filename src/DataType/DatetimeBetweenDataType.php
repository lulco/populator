<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class DatetimeBetweenDataType extends AbstractDataType
{
    protected string $startTime;

    protected string $endTime;

    protected string $format;

    public function populate(Column $column): string
    {
        return $this->faker->dateTimeBetween($this->startTime, $this->endTime)->format($this->format);
    }
}
