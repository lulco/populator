<?php

namespace Populator\Tests\Database;

use PHPUnit\Framework\TestCase;
use Populator\Database\Database;
use Populator\Tests\Behavior\CreateStructureBehavior;

abstract class AbstractDatabaseTest extends TestCase
{
    use CreateStructureBehavior;

    protected $database;

    protected function setUp(): void
    {
        $this->cleanup();
        $dsn = 'mysql:host=' . getenv('POPULATOR_MYSQL_HOST') . ';dbname=' . getenv('POPULATOR_MYSQL_DATABASE');
        $this->database = new Database(getenv('POPULATOR_MYSQL_DATABASE'), $dsn, getenv('POPULATOR_MYSQL_USERNAME'), getenv('POPULATOR_MYSQL_PASSWORD'));
    }
}
