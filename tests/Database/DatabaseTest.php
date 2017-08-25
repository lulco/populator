<?php

namespace Populator\Tests\Database;

use Populator\Database\Database;
use Populator\Exception\DatabaseConnectionException;
use Populator\Exception\TableNotFoundException;

class DatabaseTest extends AbstractDatabaseTest
{
    public function testWrongCredentials()
    {
        $dsn = 'mysql:host=' . getenv('POPULATOR_MYSQL_HOST') . ';dbname=' . getenv('POPULATOR_MYSQL_DATABASE');
        $this->expectException(DatabaseConnectionException::class);
        $this->expectExceptionMessage("SQLSTATE[HY000] [1045] Access denied for user '" . getenv('POPULATOR_MYSQL_USERNAME') . "'@'" . getenv('POPULATOR_MYSQL_HOST') . "' (using password: YES)");
        new Database(getenv('POPULATOR_MYSQL_DATABASE'), $dsn, getenv('POPULATOR_MYSQL_USERNAME'), 'wrong_password');
    }

    public function testGetName()
    {
        $this->assertEquals(getenv('POPULATOR_MYSQL_DATABASE'), $this->database->getName());
    }

    public function testGetTableStructureForNonExistingTable()
    {
        $this->expectException(TableNotFoundException::class);
        $this->expectExceptionMessage("Table 'non_existing_table' does not exist.");
        $this->database->getTableStructure('non_existing_table');
    }
}
