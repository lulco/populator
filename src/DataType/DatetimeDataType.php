<?php

namespace Populator\DataType;

class DatetimeDataType extends DatetimeBetweenDataType
{
    protected $startTime = '-2 years';

    protected $endTime = 'now';

    protected $format = 'Y-m-d H:i:s';
}
