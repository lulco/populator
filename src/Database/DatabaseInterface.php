<?php

namespace Populator\Database;

use Populator\Data\Item;
use Populator\Exception\TableNotFoundException;
use Populator\Structure\Table;

interface DatabaseInterface
{
    public function getName(): string;

    /**
     * @throws TableNotFoundException
     */
    public function getTableStructure(string $tableName): Table;

    public function insert(string $tableName, array $data): ?Item;

    public function getRandomRecord(string $tableName): ?Item;
}
