<?php

namespace Populator\Structure;

class Table
{
    /** @var string */
    private $name;

    /** @var Column[] */
    private $columns = [];

    /** @var array */
    private $primaryColumns = [];

    /** @var ForeignKey[] */
    private $foreignKeys = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addColumn(Column $column): Table
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setPrimary(array $primaryColumns = []): Table
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
        $this->foreignKeys[] = $foreignKey;
        return $this;
    }

    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }
}
