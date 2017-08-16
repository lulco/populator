<?php

namespace Populator\Data;

interface ItemInterface
{
    /**
     * @return mixed
     */
    public function getValue(string $column);
}
