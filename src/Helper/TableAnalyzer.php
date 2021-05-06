<?php

namespace Populator\Helper;

use Populator\Database\DatabaseInterface;
use Populator\Structure\Table;

class TableAnalyzer
{
    /**
     * returns depths for each table in structure
     *
     * @param array<string, Table> $structures
     * @return array<string, int>
     */
    public function getDepths(array $structures): array
    {
        $foreignKeysInTables = [];
        $sortedTables = [];
        foreach ($structures as $tableName => $structure) {
            $foreignKeys = $structure->getForeignKeys();
            if ($foreignKeys === []) {
                $sortedTables[$tableName] = 0;
                continue;
            }

            $foreignKeysInTables[$tableName] = [];

            foreach ($foreignKeys as $foreignKey) {
                $foreignKeysInTables[$tableName][] = $foreignKey->getReferencedTable();
            }
        }

        $skipTables = [];
        while (count($skipTables) !== count($foreignKeysInTables)) {
            foreach ($foreignKeysInTables as $tableName => $foreignKeysTables) {
                if (in_array($tableName, $skipTables)) {
                    continue;
                }
                $diff = array_diff($foreignKeysTables, array_keys($sortedTables));
                if ($diff === [] || $diff === [$tableName]) {
                    if ($diff === [$tableName]) {
                        $sortedTables[$tableName] = 1;
                    } else {
                        $sortedTables[$tableName] = $this->getCountOfForeignKeys($foreignKeysInTables, $foreignKeysTables);
                    }
                    $skipTables[] = $tableName;
                    break;
                }
            }
        }
        return $sortedTables;
    }

    private function getCountOfForeignKeys(array $foreignKeysInTables, array $tables): int
    {
        $count = count($tables);
        foreach ($tables as $table) {
            $count += $this->getCountOfForeignKeys($foreignKeysInTables, $foreignKeysInTables[$table] ?? []);
        }
        return $count;
    }

}
