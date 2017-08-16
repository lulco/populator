<?php

namespace Populator\Tests\Populator;

use Faker\Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Populator\Database\Database;
use Populator\Populator\AbstractPopulator;
use Populator\Populator\PopulatorInterface;
use Populator\Tests\Behavior\CreateStructureBehavior;

class PopulatorTest extends TestCase
{
    use CreateStructureBehavior;

    public function testDefaultConstruct()
    {
        $populator = $this->createPopulator('my_table');
        $this->assertEquals('my_table', $populator->getTableName());
        $this->assertEquals(10, $populator->getCount());
    }

    public function testChangedConstruct()
    {
        $populator = $this->createPopulator('my_table', 20);
        $this->assertEquals('my_table', $populator->getTableName());
        $this->assertEquals(20, $populator->getCount());
    }

    public function testAddLanguages()
    {
        $populator = $this->createPopulator('my_table');
        $this->assertEmpty($populator->getLanguages());
        $this->assertInstanceOf(PopulatorInterface::class, $populator->addLanguage('sk'));
        $this->assertCount(10, $populator->getLanguages());
        $this->assertEquals(['sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk'], $populator->getLanguages());
        $this->assertInstanceOf(PopulatorInterface::class, $populator->addLanguage('sk', 1));
        $this->assertEquals(['sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk'], $populator->getLanguages());
        $this->assertInstanceOf(PopulatorInterface::class, $populator->addLanguage('en', 5));
        $this->assertEquals(['sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'en', 'en', 'en', 'en', 'en'], $populator->getLanguages());
    }

    public function testSetEmptyDatabases()
    {
        $populator = $this->createPopulator('my_table');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Databases cannot be empty');
        $populator->setDatabases([]);
    }

    public function testSetWrongDatabases()
    {
        $populator = $this->createPopulator('my_table');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All databases must be instance of DatabaseInterface');
        $populator->setDatabases([1 => 2, 3 => 4]);
    }

    public function testSetDatabases()
    {
        $this->cleanup();

        $dsn = 'mysql:host=' . getenv('POPULATOR_MYSQL_HOST') . ';dbname=' . getenv('POPULATOR_MYSQL_DATABASE');
        $databases = [
            new Database(getenv('POPULATOR_MYSQL_DATABASE'), $dsn, getenv('POPULATOR_MYSQL_USERNAME'), getenv('POPULATOR_MYSQL_PASSWORD')),
        ];

        $populator = $this->createPopulator('my_table');
        $this->assertEmpty($populator->getDatabases());
        $this->assertInstanceOf(PopulatorInterface::class, $populator->setDatabases($databases));
        $this->assertCount(1, $populator->getDatabases());
    }


    private function createPopulator(string $table, int $count = 10, ?string $databaseIdentifier = null)
    {
        return new class($table, $count, $databaseIdentifier) extends AbstractPopulator
        {
            protected function generateData(Generator $faker): array
            {
                return [];
            }

            public function getLanguages(): array
            {
                return $this->languages;
            }

            public function getDatabases(): array
            {
                return $this->databases;
            }

            public function checkDatabase(?string $database = null)
            {
                return $this->getDatabase($database);
            }
        };
    }
}
