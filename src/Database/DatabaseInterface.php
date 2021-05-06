<?php

namespace Populator\Database;

use Populator\Data\Item;
use Populator\Exception\TableNotFoundException;
use Populator\Structure\Table;

interface DatabaseInterface
{
    public function getName(): string;

    /**
     * @return string[]
     */
    public function getTableNames(): array;

    /**
     * @throws TableNotFoundException
     */
    public function getTableStructure(string $tableName): Table;

    /**
     * @return array<string, Table>
     */
    public function getStructure(): array;

    public function insert(string $tableName, array $data): ?Item;

    public function getRandomRecord(string $tableName): ?Item;
}
