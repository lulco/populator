<?php

namespace Populator\Database;

use Nette\Caching\Storages\MemoryStorage;
use Nette\Database\Connection;
use Nette\Database\ConnectionException;
use Nette\Database\Explorer;
use Nette\Database\Structure;
use Nette\Database\Table\ActiveRow;
use Nette\InvalidArgumentException;
use Populator\Data\Item;
use Populator\Exception\DatabaseConnectionException;
use Populator\Exception\TableNotFoundException;
use Populator\Structure\Column;
use Populator\Structure\ForeignKey;
use Populator\Structure\Table;

class Database implements DatabaseInterface
{
    private string $name;

    private Explorer $database;

    /** @var array<string, Table>  */
    private array $structures = [];

    public function __construct(
        string $name,
        string $dsn,
        ?string $user = null,
        ?string $password = null,
        ?array $options = null
    ) {
        $this->name = $name;
        try {
            $connection = new Connection($dsn, $user, $password, $options ?? []);
            $cacheStorage = new MemoryStorage();
            $structure = new Structure($connection, $cacheStorage);
            $database = new Explorer($connection, $structure);
            $this->database = $database;
        } catch (ConnectionException $e) {
            throw new DatabaseConnectionException($e->getMessage(), 0, $e);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRandomRecord(string $tableName): ?Item
    {
        $count = $this->database->table($tableName)->count('*');
        if ($count === 0) {
            return null;
        }

        $table = $this->getTableStructure($tableName);
        $offset = mt_rand(0, $count - 1);
        $order = $table->getPrimary() ?: array_keys($table->getColumns());
        $record = $this->database->table($tableName)
            ->order(implode(', ', $order))
            ->limit(1, $offset)
            ->fetch();
        return $record ? new Item($record->toArray()) : null;
    }

    public function getTableNames(): array
    {
        return array_column($this->database->getStructure()->getTables(), 'name');
    }

    public function getTableStructure(string $tableName): Table
    {
        if (isset($this->structures[$tableName])) {
            return $this->structures[$tableName];
        }
        $table = new Table($tableName);
        $this->addColumnsToTable($table);
        $this->addForeignKeysToTable($table);
        $this->addPrimaryKeyToTable($table);

        $this->structures[$tableName] = $table;
        return $table;
    }

    public function getStructure(): array
    {
        $structure = [];
        foreach ($this->getTableNames() as $tableName) {
            $structure[$tableName] = $this->getTableStructure($tableName);
        }
        return $structure;
    }

    public function insert(string $tableName, array $data): ?Item
    {
        $record = $this->database->table($tableName)->insert($data);
        if (!$record) {
            return null;
        }
        $table = $this->getTableStructure($tableName);
        if ($record instanceof ActiveRow) {
            return new Item($record->toArray());
        }
        $primaryColumns = $table->getPrimary();
        if (empty($primaryColumns)) {
            return new Item($data);
        }
        $where = [];
        foreach ($primaryColumns as $primaryColumn) {
            $where[$primaryColumn] = $data[$primaryColumn];
        }
        $record = $this->database->table($tableName)->wherePrimary($where)->fetch();
        return $record ? new Item($record->toArray()) : null;
    }

    private function addColumnsToTable(Table $table): void
    {
        try {
            $columns = $this->database->getStructure()->getColumns($table->getName());
        } catch (InvalidArgumentException $e) {
            throw new TableNotFoundException($e->getMessage(), 0, $e);
        }
        foreach ($columns as $columnInfo) {
            $settings = $this->getColumnSettings($columnInfo);
            $type = $settings['type'];
            unset($settings['type']);
            $table->addColumn(new Column($columnInfo['name'], $type, $settings));
        }
    }

    private function addForeignKeysToTable(Table $table): void
    {
        $references = $this->database->getStructure()->getBelongsToReference($table->getName());
        if (!$references) {
            return;
        }
        foreach ($references as $columnName => $referencedTableName) {
            $table->addForeignKey(new ForeignKey(
                [$columnName],
                $referencedTableName,
                [$this->database->getStructure()->getPrimaryKey($referencedTableName)]   // this is not always primary key, but nette database ignores this fact
            ));
        }
    }

    private function addPrimaryKeyToTable(Table $table): void
    {
        $primaryKey = $this->database->getStructure()->getPrimaryKey($table->getName());
        if ($primaryKey && !is_array($primaryKey)) {
            $primaryKey = [$primaryKey];
        }
        if ($primaryKey && is_array($primaryKey)) {
            $table->setPrimary($primaryKey);
        }
    }

    private function getColumnSettings(array $column): array
    {
        $info = [
            'type' => $this->getType($column),
            'autoincrement' => $column['autoincrement'],
            'length' => $column['size'] ?: null,
            'unsigned' => isset($column['vendor']['type']) && strstr($column['vendor']['type'], 'unsigned'),
            'nullable' => $column['nullable'],
            'default' => ($column['nullable'] && $column['default'] === null) || $column['default'] !== null ? $column['default'] : null,
        ];

        $extendedInfo = $this->getExtendedColumnSettings($column);
        return array_merge($info, $extendedInfo);
    }

    private function getType(array $column): string
    {
        $type = explode(' ', strtolower($column['nativetype']))[0];
        $types = [
            'bool' => 'boolean',
            'int' => 'integer',
            'int4' => 'integer',
            'float4' => 'float',
            'float8' => 'double',
            'tinyint' => 'tinyinteger',
            'smallint' => 'smallinteger',
            'mediumint' => 'mediuminteger',
            'bigint' => 'biginteger',
            'varchar' => 'string',
            'timestamp' => 'datetime',
        ];

        if (isset($types[$type])) {
            $type = $types[$type];
        }

        if ($type == 'tinyinteger' && $column['size'] == 1) {
            return 'boolean';
        }
        if ($type == 'char' && $column['size'] == 36) {
            return 'uuid';
        }
        return $type;
    }

    private function getExtendedColumnSettings(array $column): array
    {
        if (!isset($column['vendor']['type'])) {
            return [];
        }

        if (strpos($column['vendor']['type'], '(') === false) {
            return [];
        }

        $extendedInfo = [];

        $fullType = $column['vendor']['type'];
        $pattern = '/(.*?)\((.*?)\)(.*)/';
        preg_match($pattern, $fullType, $matches);
        /** @var array<int, string> $matches */
        $type = trim($matches[1]);
        if (in_array($type, ['enum', 'set'])) {
            $values = str_replace("'", '', $matches[2]);
            $extendedInfo['values'] = explode(',', $values);
        } else {
            $length = trim($matches[2]);
            $lengthParts = explode(',', $length);
            if (count($lengthParts) == 2) {
                $extendedInfo['decimals'] = $lengthParts[1];
            }
        }
        return $extendedInfo;
    }
}
