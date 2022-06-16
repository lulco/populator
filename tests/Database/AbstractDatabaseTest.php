<?php

namespace Populator\Tests\Database;

use PHPUnit\Framework\TestCase;
use Populator\Database\Database;
use Populator\Tests\Behavior\CreateStructureBehavior;

abstract class AbstractDatabaseTest extends TestCase
{
    use CreateStructureBehavior;

    protected Database $database;

    protected function setUp(): void
    {
        $this->cleanup();
        $dsn = getenv('POPULATOR_ADAPTER') . ':host=' . getenv('POPULATOR_HOST') . ';port=' . getenv('POPULATOR_PORT') . ';dbname=' . getenv('POPULATOR_DATABASE');
        $this->database = new Database(getenv('POPULATOR_DATABASE'), $dsn, getenv('POPULATOR_USERNAME'), getenv('POPULATOR_PASSWORD'));
    }
}
