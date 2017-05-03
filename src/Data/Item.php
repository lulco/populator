<?php

namespace Populator\Data;

use Populator\Structure\Table;

class Item implements ItemInterface
{
    private $table;

    private $data;

    public function __construct(Table $table, array $data)
    {
        $this->table = $table;
        $this->data = $data;
    }

    public function getData($column)
    {
        return $this->data[$column] ?? null;
    }

    public function getId()
    {
        $primaryColumns = $this->table->getPrimary();
        if (count($primaryColumns) === 1) {
            $primaryColumn = current($primaryColumns);
            return $this->data[$primaryColumn];
        }
        $id = [];
        foreach ($primaryColumns as $primaryColumn) {
            $id[$primaryColumn] = $this->data[$primaryColumn];
        }
        return $id;
    }
}
