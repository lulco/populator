<?php

namespace Populator\DataType;

class DatetimeDataType extends DatetimeBetweenDataType
{
    protected string $startTime = '-2 years';

    protected string $endTime = 'now';

    protected string $format = 'Y-m-d H:i:s';
}
