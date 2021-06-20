<?php

namespace Populator\Tests\Populator;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Populator\Database\Database;
use Populator\Database\DatabaseInterface;
use Populator\Event\EventInterface;
use Populator\Event\ProgressBarEvent;
use Populator\Event\SimpleInfoEvent;
use Populator\Populator\AutomaticPopulator;
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

    public function testSetLanguages()
    {
        $populator = $this->createPopulator('my_table');
        $this->assertEmpty($populator->getLanguages());
        $populator->setLanguages(['sk', 'en']);
        $this->assertEquals(['sk', 'en'], $populator->getLanguages());

        $populator = $this->createPopulator('my_table');
        $this->assertEmpty($populator->getLanguages());
        $this->assertInstanceOf(PopulatorInterface::class, $populator->addLanguage('sk'));
        $this->assertEquals(['sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk'], $populator->getLanguages());
        $populator->setLanguages(['sk', 'en']);
        $this->assertEquals(['sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk', 'sk'], $populator->getLanguages());
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

        $dsn = 'mysql:host=' . getenv('POPULATOR_MYSQL_HOST') . ';port=' . getenv('POPULATOR_MYSQL_PORT') . ';dbname=' . getenv('POPULATOR_MYSQL_DATABASE');
        $databases = [
            new Database(getenv('POPULATOR_MYSQL_DATABASE'), $dsn, getenv('POPULATOR_MYSQL_USERNAME'), getenv('POPULATOR_MYSQL_PASSWORD')),
        ];

        $populator = $this->createPopulator('my_table');
        $this->assertEmpty($populator->getDatabases());
        $this->assertInstanceOf(PopulatorInterface::class, $populator->setDatabases($databases));
        $this->assertCount(1, $populator->getDatabases());
        $this->assertInstanceOf(DatabaseInterface::class, $populator->checkDatabase());
    }

    public function testAddEvent()
    {
        $populator = $this->createPopulator('my_table');
        $this->assertEmpty($populator->getEvents());
        $this->assertInstanceOf(PopulatorInterface::class, $populator->addEvent(new SimpleInfoEvent()));
        $events = $populator->getEvents();
        $this->assertCount(1, $events);
        foreach ($events as $event) {
            $this->assertInstanceOf(EventInterface::class, $event);
        }

        $this->assertInstanceOf(PopulatorInterface::class, $populator->addEvent(new ProgressBarEvent()));
        $events = $populator->getEvents();
        $this->assertCount(2, $events);
        foreach ($events as $event) {
            $this->assertInstanceOf(EventInterface::class, $event);
        }
    }

    public function testPopulate()
    {
        $this->cleanup();

        $dsn = 'mysql:host=' . getenv('POPULATOR_MYSQL_HOST') . ';port=' . getenv('POPULATOR_MYSQL_PORT') . ';dbname=' . getenv('POPULATOR_MYSQL_DATABASE');
        $databases = [
            new Database(getenv('POPULATOR_MYSQL_DATABASE'), $dsn, getenv('POPULATOR_MYSQL_USERNAME'), getenv('POPULATOR_MYSQL_PASSWORD')),
        ];

        $this->createSimpleTable();

        $populator = $this->createPopulator('simple', 5);
        $this->assertInstanceOf(PopulatorInterface::class, $populator->setDatabases($databases));
        $this->assertEquals(5, $populator->populate());

        $populator = $this->createPopulator('simple', 5);
        $this->assertInstanceOf(PopulatorInterface::class, $populator->setDatabases($databases));
        $this->assertInstanceOf(PopulatorInterface::class, $populator->setLanguages(['sk', 'en']));
        $this->assertEquals(5, $populator->populate());
    }

    private function createPopulator(string $table, int $count = 10, ?string $databaseIdentifier = null)
    {
        return new class($table, $count, $databaseIdentifier) extends AutomaticPopulator
        {
            public function getLanguages(): array
            {
                return $this->languages;
            }

            public function getDatabases(): array
            {
                return $this->databases;
            }

            public function getEvents(): array
            {
                return $this->events;
            }

            public function checkDatabase(?string $database = null): DatabaseInterface
            {
                return $this->getDatabase($database);
            }
        };
    }
}
