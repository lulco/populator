<?php

namespace Populator\Data;

class Item implements ItemInterface
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getValue(string $column)
    {
        return $this->data[$column] ?? null;
    }
}
