<?php

namespace Populator\Helper;

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
        ksort($structures);

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
                $uniqueDiff = array_values(array_unique($diff));
                if ($diff === [] || $uniqueDiff === [$tableName]) {
                    if ($uniqueDiff === [$tableName]) {
                        $sortedTables[$tableName] = count($diff) + 1;
                    } else {
                        $sortedTables[$tableName] = $this->getCountOfForeignKeys($foreignKeysTables, $sortedTables);
                    }
                    $skipTables[] = $tableName;
                    break;
                }
            }
        }
        return $sortedTables;
    }

    private function getCountOfForeignKeys(array $foreignKeysTables, array $sortedTables): int
    {
        $counts = [];
        foreach ($foreignKeysTables as $foreignKeysTable) {
            $counts[] = $sortedTables[$foreignKeysTable] ?? 0;
        }
        return max($counts) + 1;
    }
}
