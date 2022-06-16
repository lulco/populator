<?php

namespace Populator\Structure;

class Table
{
    private string $name;

    /** @var Column[] */
    private array $columns = [];

    /** @var string[] */
    private array $primaryColumns = [];

    /** @var ForeignKey[] */
    private array $foreignKeys = [];

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

    /**
     * @param string[] $primaryColumns
     */
    public function setPrimary(array $primaryColumns = []): Table
    {
        $this->primaryColumns = $primaryColumns;
        return $this;
    }

    /**
     * @return string[]
     */
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
