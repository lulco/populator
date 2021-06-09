<?php

namespace Populator\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Populator\Helper\TableAnalyzer;
use Populator\Structure\Column;
use Populator\Structure\ForeignKey;
use Populator\Structure\Table;

class TableAnalyzerTest extends TestCase
{
    private $tableAnalyzer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tableAnalyzer = new TableAnalyzer();
    }

    public function testNoForeignKeys(): void
    {
        $simpleStructures = [
            'table_1' => (new Table('table_1'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
            'table_2' => (new Table('table_2'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
            'table_3' => (new Table('table_3'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
        ];

        $simpleTableDepths = $this->tableAnalyzer->getDepths($simpleStructures);
        $this->assertEquals(['table_1', 'table_2', 'table_3'], array_keys($simpleTableDepths));
        $this->assertEquals(['table_1' => 0, 'table_2' => 0, 'table_3' => 0], $simpleTableDepths);

        // should be returned alphabetically
        $simpleStructuresChangedOrder = [
            'table_3' => (new Table('table_3'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
            'table_1' => (new Table('table_1'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
            'table_2' => (new Table('table_2'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
        ];

        $simpleTableChangedOrderDepths = $this->tableAnalyzer->getDepths($simpleStructuresChangedOrder);
        $this->assertEquals(['table_1', 'table_2', 'table_3'], array_keys($simpleTableChangedOrderDepths));
        $this->assertEquals(['table_1' => 0, 'table_2' => 0, 'table_3' => 0], $simpleTableChangedOrderDepths);
    }

    public function testOneForeignKeyFromTwoTablesToThirdTable(): void
    {
        $structures = [
            'table_1' => (new Table('table_1'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
            'table_2' => (new Table('table_2'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true]))
                ->addColumn(new Column('fk_table_1_id', 'int'))
                ->addForeignKey(new ForeignKey(['fk_table_1_id'], 'table_1', ['id'])),
            'table_3' => (new Table('table_3'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true]))
                ->addColumn(new Column('fk_table_1_id', 'int'))
                ->addForeignKey(new ForeignKey(['fk_table_1_id'], 'table_1', ['id'])),
        ];

        $tableDepths = $this->tableAnalyzer->getDepths($structures);
        $this->assertEquals(['table_1', 'table_2', 'table_3'], array_keys($tableDepths));
        $this->assertEquals(['table_1' => 0, 'table_2' => 1, 'table_3' => 1], $tableDepths);
    }

    public function testForeignKeysChaining(): void
    {
        $structures = [
            'table_1' => (new Table('table_1'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
            'table_2' => (new Table('table_2'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true]))
                ->addColumn(new Column('fk_table_3_id', 'int'))
                ->addForeignKey(new ForeignKey(['fk_table_3_id'], 'table_3', ['id'])),
            'table_3' => (new Table('table_3'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true]))
                ->addColumn(new Column('fk_table_1_id', 'int'))
                ->addForeignKey(new ForeignKey(['fk_table_1_id'], 'table_1', ['id'])),
        ];

        $tableDepths = $this->tableAnalyzer->getDepths($structures);
        $this->assertEquals(['table_1', 'table_3', 'table_2'], array_keys($tableDepths));
        $this->assertEquals(['table_1' => 0, 'table_3' => 1, 'table_2' => 2], $tableDepths);
    }

    public function testMultipleForeignKeys(): void
    {
        $structures = [
            'table_1' => (new Table('table_1'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true])),
            'table_2' => (new Table('table_2'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true]))
                ->addColumn(new Column('fk_table_1_id', 'int'))
                ->addForeignKey(new ForeignKey(['fk_table_1_id'], 'table_1', ['id'])),
            'table_3' => (new Table('table_3'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true]))
                ->addColumn(new Column('fk_table_1_id', 'int'))
                ->addColumn(new Column('fk_table_2_id', 'int'))
                ->addForeignKey(new ForeignKey(['fk_table_1_id'], 'table_1', ['id']))
                ->addForeignKey(new ForeignKey(['fk_table_2_id'], 'table_2', ['id'])),
        ];

        $tableDepths = $this->tableAnalyzer->getDepths($structures);
        $this->assertEquals(['table_1', 'table_2', 'table_3'], array_keys($tableDepths));
        $this->assertEquals(['table_1' => 0, 'table_2' => 1, 'table_3' => 2], $tableDepths);
    }

    public function testMultipleSelfForeignKeys(): void
    {
        $structures = [
            'pages' => (new Table('pages'))
                ->addColumn(new Column('id', 'int', ['autoincrement' => true]))
                ->addColumn(new Column('parent_id', 'int', ['nullable' => true]))
                ->addColumn(new Column('shortcut_id', 'int', ['nullable' => true]))
                ->addForeignKey(new ForeignKey(['parent_id'], 'pages', ['id']))
                ->addForeignKey(new ForeignKey(['shortcut_id'], 'pages', ['id'])),
        ];

        $tableDepths = $this->tableAnalyzer->getDepths($structures);
        $this->assertEquals(['pages'], array_keys($tableDepths));
        $this->assertEquals(['pages' => 3], $tableDepths);
    }
}
