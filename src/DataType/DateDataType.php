<?php

namespace Populator\DataType;

class DateDataType extends DatetimeBetweenDataType
{
    protected string $startTime = '-2 years';

    protected string $endTime = 'now';

    protected string $format = 'Y-m-d';
}
