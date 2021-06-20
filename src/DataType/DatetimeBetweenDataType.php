<?php

namespace Populator\DataType;

use Populator\Structure\Column;

class DatetimeBetweenDataType extends AbstractDataType
{
    /** @var string */
    protected $startTime;

    /** @var string */
    protected $endTime;

    /** @var string */
    protected $format;

    public function populate(Column $column): string
    {
        return $this->faker->dateTimeBetween($this->startTime, $this->endTime)->format($this->format);
    }
}
