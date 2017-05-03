<?php

namespace Populator\DataType;

class DateDataType extends DatetimeBetweenDataType
{
    protected $startTime = '-2 years';

    protected $endTime = 'now';

    protected $format = 'Y-m-d';
}
