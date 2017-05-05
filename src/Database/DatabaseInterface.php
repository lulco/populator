<?php

namespace Populator\Database;

use Populator\Data\Item;
use Populator\Structure\Table;

interface DatabaseInterface
{
    public function getName(): string;

    public function getStructure(string $tableName): Table;

    public function insert(string $tableName, array $data): Item;

    public function getRandomRecord(string $tableName): ?Item;
}
