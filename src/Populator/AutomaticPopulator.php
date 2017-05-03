<?php

namespace Populator\Populator;

use Exception;
use Faker\Generator;
use Populator\DataType\DataTypeInterface;
use Populator\Structure\Column;

class AutomaticPopulator extends AbstractPopulator
{
    protected $columnNameClasses = [];

    protected $dataTypeClasses = [];

    protected function generateData(Generator $faker): array
    {
        $database = $this->getDatabase();
        $structure = $database->getStructure($this->table);

        $data = [];
        foreach ($structure->getForeignKeys() as $foreignKey) {
            // TODO - get database base on referenced database
            $item = $database->getRandomRecord($foreignKey->getReferencedTable());
            $values = [];
            foreach ($foreignKey->getReferencedColumns() as $foreignColumn) {
                $values[] = $item ? $item->getData($foreignColumn) : null;
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
        $dataTypeClass = $this->findDataTypeClassName($column, $faker);
        return $dataTypeClass->populate($column);
    }

    private function findDataTypeClassName(Column $column, Generator $faker)
    {
        $className = null;
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

        if ($className instanceof DataTypeInterface) {
            return $className;
        }

        if (!class_exists($className)) {
            throw new Exception('Data type "' . $className . '" doesn\'t exist');
        }

        return new $className($faker);
    }
}
