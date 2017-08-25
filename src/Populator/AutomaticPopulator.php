<?php

namespace Populator\Populator;

use Closure;
use Exception;
use Faker\Generator;
use Populator\DataType\DataTypeInterface;
use Populator\Structure\Column;

class AutomaticPopulator extends AbstractPopulator
{
    protected $columnNameClasses = [];

    protected $dataTypeClasses = [];

    protected $nullableValueProbability;

    protected $defaultValueProbability;

    public function __construct(
        $table,
        $count = 10,
        $databaseIdentifier = null,
        $nullableValueProbability = 25,
        $defaultValueProbability = 25
    ) {
        parent::__construct($table, $count, $databaseIdentifier);
        $this->nullableValueProbability = $nullableValueProbability;
        $this->defaultValueProbability = $defaultValueProbability;
    }

    protected function generateData(Generator $faker): array
    {
        $database = $this->getDatabase();
        $structure = $database->getTableStructure($this->table);

        $data = [];
        foreach ($structure->getForeignKeys() as $foreignKey) {
            $foreignKeyDatabase = $this->getDatabase($foreignKey->getReferencedDatabase());
            $item = $foreignKeyDatabase->getRandomRecord($foreignKey->getReferencedTable());
            $values = [];
            foreach ($foreignKey->getReferencedColumns() as $foreignColumn) {
                $values[] = $item ? $item->getValue($foreignColumn) : null;
            }
            $data += array_combine($foreignKey->getColumns(), $values);
        }
        foreach ($structure->getColumns() as $column) {
            if (array_key_exists($column->getName(), $data)) {
                continue;
            }
            if ($column->isAutoincrement()) {
                continue;
            }
            $data[$column->getName()] = $this->createValue($column, $faker);
        }
        return $data;
    }

    private function createValue(Column $column, Generator $faker)
    {
        if ($column->isNullable() && $faker->boolean($this->nullableValueProbability)) {
            return null;
        }

        if ($column->getDefault() && $faker->boolean($this->defaultValueProbability)) {
            return $column->getDefault();
        }

        $dataTypeClass = $this->findDataTypeClassName($column, $faker);
        if ($dataTypeClass instanceof Closure) {
            return $dataTypeClass($column, $faker);
        }
        return $dataTypeClass->populate($column);
    }

    private function findDataTypeClassName(Column $column, Generator $faker)
    {
        if (isset($this->columnNameClasses[$column->getName()])) {
            $className = $this->columnNameClasses[$column->getName()];
        } elseif (isset($this->dataTypeClasses[$column->getType()])) {
            $className = $this->dataTypeClasses[$column->getType()];
        } else {
            $className = '\\Populator\\DataType\\' . ucfirst($column->getType()) . 'DataType';
        }

        if (!$className) {
            throw new Exception('Data type class for type "' . $column->getType() . '" and name "' . $column->getName() . '" not found');
        }

        if ($className instanceof DataTypeInterface || $className instanceof Closure) {
            return $className;
        }

        if (!class_exists($className)) {
            throw new Exception('Data type "' . $className . '" doesn\'t exist');
        }

        return new $className($faker);
    }
}
