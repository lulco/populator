<?php

namespace Populator\Tests\Database;

use Populator\Database\Database;
use Populator\Exception\DatabaseConnectionException;
use Populator\Exception\TableNotFoundException;

class DatabaseTest extends AbstractDatabaseTest
{
    public function testWrongCredentials(): void
    {
        $adapter = getenv('POPULATOR_ADAPTER');
        $dsn = $adapter . ':host=' . getenv('POPULATOR_HOST') . ';port=' . getenv('POPULATOR_PORT') . ';dbname=' . getenv('POPULATOR_DATABASE');
        $this->expectException(DatabaseConnectionException::class);
        if ($adapter === 'pgsql') {
            $this->expectExceptionMessage('password authentication failed for user "' . getenv('POPULATOR_USERNAME') . '"');
        } else {
            $this->expectExceptionMessage("SQLSTATE[HY000] [1045] Access denied for user '" . getenv('POPULATOR_USERNAME') . "'@'");
        }
        new Database(getenv('POPULATOR_DATABASE'), $dsn, getenv('POPULATOR_USERNAME'), 'wrong_password');
    }

    public function testGetName(): void
    {
        $this->assertEquals(getenv('POPULATOR_DATABASE'), $this->database->getName());
    }

    public function testGetTableStructureForNonExistingTable(): void
    {
        $this->expectException(TableNotFoundException::class);
        $this->expectExceptionMessage("Table 'non_existing_table' does not exist.");
        $this->database->getTableStructure('non_existing_table');
    }
}
