<?php

namespace Populator\DataType;

class LongblobDataType extends BlobDataType
{
    protected int $max = 4294967295;
}
