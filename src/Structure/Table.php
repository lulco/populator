<?php

namespace Populator\Structure;

class Table
{
    /** @var Column */
    private $columns = [];

    /** @var array */
    private $primaryColumns = [];

    /** @var ForeignKey[] */
    private $foreignKeys = [];

    public function addColumn(Column $column): Table
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setPrimary(array $primaryColumns = [])
    {
        $this->primaryColumns = $primaryColumns;
        return $this;
    }

    public function getPrimary(): array
    {
        return $this->primaryColumns;
    }

    public function addForeignKey(ForeignKey $foreignKey): Table
    {
        $this->foreignKeys[implode('|', $foreignKey->getColumns())] = $foreignKey;
        return $this;
    }

    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }
}
