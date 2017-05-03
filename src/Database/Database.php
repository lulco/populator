<?php

namespace Populator\Database;

use Nette\Caching\Storages\MemoryStorage;
use Nette\Database\Connection;
use Nette\Database\Context;
use Nette\Database\Structure;
use Nette\Database\Table\ActiveRow;
use Populator\Data\Item;
use Populator\Database\DatabaseInterface;
use Populator\Structure\Column;
use Populator\Structure\ForeignKey;
use Populator\Structure\Table;

class Database implements DatabaseInterface
{
    private $name;

    private $databaseContext;

    private $structures = [];

    public function __construct(
        string $name,
        string $dsn,
        ?string $user = null,
        ?string $password = null,
        ?array $options = null
    ) {
        $this->name = $name;
        $connection = new Connection($dsn, $user, $password, $options);
        $cacheStorage = new MemoryStorage();
        $structure = new Structure($connection, $cacheStorage);
        $databaseContext = new Context($connection, $structure);
        $this->databaseContext = $databaseContext;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRandomRecord(string $tableName, array $conditions = []): ?Item
    {
        $table = $this->getStructure($tableName);
        $where = $this->createWhere($conditions);
        $count = $where ?
            $this->databaseContext->table($tableName)->where($where)->count('*') :
            $this->databaseContext->table($tableName)->count('*');

        if ($count == 0) {
            return null;
        }

        $offset = mt_rand(0, $count - 1);
        $record = $this->databaseContext->table($tableName);
        $oneRecord = $where ?
                $record->where($where)->limit(1, $offset)->fetch() :
                $record->limit(1, $offset)->fetch();

        return $oneRecord ? new Item($table, $oneRecord->toArray()) : null;
    }

    public function getStructure(string $tableName): Table
    {
        if (isset($this->structures[$tableName])) {
            return $this->structures[$tableName];
        }
        $table = new Table();
        $columns = $this->databaseContext->getStructure()->getColumns($tableName);
        foreach ($columns as $columnInfo) {
            $settings = $this->getColumnSettings($columnInfo);
            $type = $settings['type'];
            unset($settings['type']);
            $table->addColumn(new Column($columnInfo['name'], $type, $settings));
        }

        $references = $this->databaseContext->getStructure()->getBelongsToReference($tableName);
        foreach ($references as $columnName => $referencedTableName) {
            $table->addForeignKey(new ForeignKey(
                [$columnName],
                $referencedTableName,
                [$this->databaseContext->getStructure()->getPrimaryKey($referencedTableName)]
            ));
        }
        $primaryKey = $this->databaseContext->getStructure()->getPrimaryKey($tableName);
        if ($primaryKey && !is_array($primaryKey)) {
            $primaryKey = [$primaryKey];
        }
        $table->setPrimary($primaryKey);
        $this->structures[$tableName] = $table;
        return $table;
    }

    public function insert(string $tableName, array $data): Item
    {
        $table = $this->getStructure($tableName);
        $record = $this->databaseContext->table($tableName)->insert($data);
        if (!$record) {
            return null;
        }
        if ($record instanceof ActiveRow) {
            return new Item($table, $record->toArray());
        }
        $primaryColumns = $table->getPrimary();
        $where = [];
        foreach ($primaryColumns as $primaryColumn) {
            $where[$primaryColumn] = $data[$primaryColumn];
        }
        $record = !empty($where) ? $this->databaseContext->table($tableName)->where($where)->fetch() : null;
        return $record ? new Item($table, $record->toArray()) : null;
    }

    public function update(string $tableName, array $data, array $conditions = array()): Item
    {

    }

    private function getColumnSettings(array $column)
    {
        $info = [
            'type' => $this->getType($column),
            'autoincrement' => $column['autoincrement'],
            'length' => $column['size'],
            'unsigned' => $column['unsigned'],
            'null' => $column['nullable'],
            'default' => ($column['nullable'] && $column['default'] === null) || $column['default'] !== null ? $column['default'] : false,
        ];

        $extendedInfo = $this->getExtendedColumnSettings($column);
        return array_merge($info, $extendedInfo);
    }

    private function getType(array $column)
    {
        $type = strtolower($column['nativetype']);
        $types = [
            'int' => 'integer',
            'tinyint' => 'tinyinteger',
            'smallint' => 'smallinteger',
            'mediumint' => 'mediuminteger',
            'bigint' => 'biginteger',
            'varchar' => 'string',
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

    private function getExtendedColumnSettings($column)
    {
        if (!isset($column['vendor']['Type'])) {
            return [];
        }

        if (strpos($column['vendor']['Type'], '(') === false) {
            return [];
        }

        $extendedInfo = [];

        $fullType = $column['vendor']['Type'];
        $pattern = '/(.*?)\((.*?)\)(.*)/';
        preg_match($pattern, $fullType, $matches);
        $type = trim($matches[1]);
        if (in_array($type, array('enum', 'set'))) {
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

    private function createWhere(array $condition)
    {
        $where = [];
        return $where;
    }
}
