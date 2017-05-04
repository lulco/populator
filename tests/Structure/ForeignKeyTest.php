<?php

namespace Populator\Tests\Structure;

use PHPUnit\Framework\TestCase;
use Populator\Structure\ForeignKey;

class ForeignKeyTest extends TestCase
{
    public function testSingleForeignKeySettings()
    {
        $foreignKey = new ForeignKey(['fk_column'], 'referenced_table');
        $this->assertInstanceOf(ForeignKey::class, $foreignKey);
        $this->assertEquals(['fk_column'], $foreignKey->getColumns());
        $this->assertEquals('referenced_table', $foreignKey->getReferencedTable());
        $this->assertEquals(['id'], $foreignKey->getReferencedColumns());
        $this->assertNull($foreignKey->getReferencedDatabase());
    }

    public function testMultiForeignKeySettings()
    {
        $foreignKey = new ForeignKey(['column_1', 'column_2'], 'referenced_table', ['ref_col_1', 'ref_col_2']);
        $this->assertInstanceOf(ForeignKey::class, $foreignKey);
        $this->assertEquals(['column_1', 'column_2'], $foreignKey->getColumns());
        $this->assertEquals('referenced_table', $foreignKey->getReferencedTable());
        $this->assertEquals(['ref_col_1', 'ref_col_2'], $foreignKey->getReferencedColumns());
        $this->assertNull($foreignKey->getReferencedDatabase());
    }

    public function testOtherDatabaseSingleForeignKeySettings()
    {
        $foreignKey = new ForeignKey(['fk_column'], 'referenced_table', ['id'], 'referenced_database');
        $this->assertInstanceOf(ForeignKey::class, $foreignKey);
        $this->assertEquals(['fk_column'], $foreignKey->getColumns());
        $this->assertEquals('referenced_table', $foreignKey->getReferencedTable());
        $this->assertEquals(['id'], $foreignKey->getReferencedColumns());
        $this->assertEquals('referenced_database', $foreignKey->getReferencedDatabase());
    }
}
