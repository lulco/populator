<?php

namespace Populator\Structure;

class ForeignKey
{
    private array $columns;

    private string $referencedTable;

    private array $referencedColumns;

    private ?string $referencedDatabase;

    public function __construct(array $columns, string $referencedTable, array $referencedColumns = ['id'], ?string $referencedDatabase = null)
    {
        $this->columns = $columns;
        $this->referencedTable = $referencedTable;
        $this->referencedColumns = $referencedColumns;
        $this->referencedDatabase = $referencedDatabase;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getReferencedTable(): string
    {
        return $this->referencedTable;
    }

    public function getReferencedColumns(): array
    {
        return $this->referencedColumns;
    }

    public function getReferencedDatabase(): ?string
    {
        return $this->referencedDatabase;
    }
}
