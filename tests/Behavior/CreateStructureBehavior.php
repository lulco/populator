<?php

namespace Populator\Tests\Behavior;

use PDO;
use Phoenix\Database\Adapter\MysqlAdapter;
use Phoenix\Database\Adapter\PgsqlAdapter;
use Phoenix\Database\Element\ForeignKey;
use Phoenix\Database\Element\Index;
use Phoenix\Database\Element\MigrationTable;
use Phoenix\Database\QueryBuilder\MysqlQueryBuilder;
use Phoenix\Database\QueryBuilder\PgsqlQueryBuilder;

trait CreateStructureBehavior
{
    /** @var array<string, PDO>  */
    private array $pdoList = [];

    protected function cleanup(): void
    {
        $pdo = $this->getPdo();
        $adapter = getenv('POPULATOR_ADAPTER');
        $database = getenv('POPULATOR_DATABASE');
        $charset = getenv('POPULATOR_CHARSET');
        $collation = getenv('POPULATOR_COLLATION');
        if ($adapter === 'pgsql') {
            $pdo->query(sprintf("SELECT pg_terminate_backend (pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = '%s'", $database));
            $pdo->query(sprintf('DROP DATABASE IF EXISTS %s', $database));
            $pdo->query(sprintf("SELECT pg_terminate_backend (pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = '%s'", $database));
            $pdo->query(sprintf('CREATE DATABASE %s', $database));
        } else {
            $pdo->query(sprintf('DROP DATABASE IF EXISTS `%s`;', $database));
            $pdo->query(sprintf('CREATE DATABASE `%s` CHARACTER SET %s COLLATE %s;', $database, $charset, $collation));
        }
    }

    protected function createSimpleTable(): void
    {
        $migrationTable = (new MigrationTable('simple'))
            ->addColumn('created_at', 'datetime')
            ->addColumn('title', 'string')
            ->addColumn('type', 'string', ['null' => true, 'default' => 'type1'])
            ->addColumn('sorting', 'integer', ['null' => true, 'length' => 10])
            ->addColumn('price', 'double', ['length' => 10, 'decimals' => 2])
        ;
        $migrationTable->create();
        $this->createAndExecuteQueries($migrationTable);
    }

    protected function createNoPrimaryKeysTable(): void
    {
        $migrationTable = (new MigrationTable('no_primary_key', false))
            ->addColumn('meta_key', 'string')
            ->addColumn('meta_value', 'string', ['null' => true])
        ;
        $migrationTable->create();
        $this->createAndExecuteQueries($migrationTable);
    }

    protected function createMultiplePrimaryKeysTable(): void
    {
        $migrationTable = (new MigrationTable('multiple_primary_keys', ['pk1', 'pk2']))
            ->addColumn('pk1', 'integer')
            ->addColumn('pk2', 'uuid')
            ->addColumn('title', 'string')
            ->addColumn('is_active', 'boolean', ['default' => false])
        ;
        $migrationTable->create();
        $this->createAndExecuteQueries($migrationTable);
    }

    protected function createStructureWithForeignKeys(): void
    {
        $migrationTable = (new MigrationTable('table_1'))
            ->addColumn('is_active', 'boolean')
            ->addColumn('title', 'string')
        ;
        $migrationTable->create();
        $this->createAndExecuteQueries($migrationTable);

        $migrationTable = (new MigrationTable('table_2'))
            ->addColumn('title', 'string')
            ->addColumn('alias', 'string')
            ->addIndex('alias', Index::TYPE_UNIQUE)
        ;
        $migrationTable->create();
        $this->createAndExecuteQueries($migrationTable);

        $migrationTable = (new MigrationTable('table_3'))
            ->addColumn('fk_t1_id', 'integer')
            ->addColumn('fk_t2_id', 'integer')
            ->addForeignKey('fk_t1_id', 'table_1', 'id', ForeignKey::CASCADE, ForeignKey::CASCADE)
            ->addForeignKey('fk_t2_id', 'table_2', 'id', ForeignKey::CASCADE, ForeignKey::CASCADE)
        ;
        $migrationTable->create();
        $this->createAndExecuteQueries($migrationTable);
    }

    private function getPdo(?string $database = null): PDO
    {
        if (isset($this->pdoList[$database])) {
            return $this->pdoList[$database];
        }

        $dsn = getenv('POPULATOR_ADAPTER') . ':host=' . getenv('POPULATOR_HOST') . ';port=' . getenv('POPULATOR_PORT');
        if ($database) {
            $dsn .= ';dbname=' . $database;
        }
        $pdo = new PDO($dsn, getenv('POPULATOR_USERNAME'), getenv('POPULATOR_PASSWORD'));
        $this->pdoList[$database] = $pdo;

        return $pdo;
    }

    private function createAndExecuteQueries(MigrationTable $migrationTable): void
    {
        $pdo = $this->getPdo(getenv('POPULATOR_DATABASE'));
        $adapter = getenv('POPULATOR_ADAPTER');
        if ($adapter === 'pgsql') {
            $queryBuilder = new PgsqlQueryBuilder(new PgsqlAdapter($pdo));
        } else {
            $queryBuilder = new MysqlQueryBuilder(new MysqlAdapter($pdo));
        }

        $queries = $queryBuilder->createTable($migrationTable);
        foreach ($queries as $query) {
            $pdo->query($query);
        }
    }
}
