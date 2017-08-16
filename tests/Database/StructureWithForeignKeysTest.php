<?php

namespace Populator\Tests\Database;

use Exception;
use Populator\Structure\ForeignKey;
use Populator\Structure\Table;

class StructureWithForeignKeysTest extends AbstractDatabaseTest
{
    public function testGetTablesStructure()
    {
        $this->createStructureWithForeignKeys();

        $table1 = $this->database->getTableStructure('table_1');
        $this->assertInstanceOf(Table::class, $table1);
        $this->assertEquals('table_1', $table1->getName());

        $this->assertNotEmpty($table1->getColumns());
        $this->assertEmpty($table1->getForeignKeys());


        $table2 = $this->database->getTableStructure('table_2');
        $this->assertInstanceOf(Table::class, $table2);
        $this->assertEquals('table_2', $table2->getName());

        $this->assertNotEmpty($table2->getColumns());
        $this->assertEmpty($table2->getForeignKeys());


        $table3 = $this->database->getTableStructure('table_3');
        $this->assertInstanceOf(Table::class, $table3);
        $this->assertEquals('table_3', $table3->getName());

        $this->assertNotEmpty($table3->getColumns());
        $foreignKeys = $table3->getForeignKeys();
        $this->assertNotEmpty($foreignKeys);

        foreach ($foreignKeys as $foreignKey) {
            $this->assertInstanceOf(ForeignKey::class, $foreignKey);
            $foreignKeyColumns = $foreignKey->getColumns();
            $this->assertNotEmpty($foreignKeyColumns);

            if (in_array('fk_t1_id', $foreignKeyColumns)) {
                $this->assertEquals('table_1', $foreignKey->getReferencedTable());
                $this->assertEquals(['id'], $foreignKey->getReferencedColumns());
                $this->assertNull($foreignKey->getReferencedDatabase());
            } elseif (in_array('fk_t2_id', $foreignKeyColumns)) {
                $this->assertEquals('table_2', $foreignKey->getReferencedTable());
                $this->assertEquals(['id'], $foreignKey->getReferencedColumns());
                $this->assertNull($foreignKey->getReferencedDatabase());
            } else {
                throw new Exception('Unknown foreign key');
            }
        }
    }
}
