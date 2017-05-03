<?php

namespace Populator\Populator;

use Exception;
use Faker\Factory;
use Faker\Generator;
use Populator\Database\DatabaseInterface;
use Populator\Event\EventInterface;

abstract class AbstractPopulator implements PopulatorInterface
{
    /** @var DatabaseInterface[] */
    protected $databases = [];

    /** @var DatabaseInterface */
    private $database;

    /** @var array */
    protected $languages = [];

    /** @var EventInterface[] */
    protected $events = [];

    /** @var string */
    protected $table;

    /** @var int */
    protected $count;

    /** @var string */
    protected $databaseIdentifier;

    /** @var Generator */
    protected $faker;

    public function __construct($table, $count = 10, $databaseIdentifier = null)
    {
        $this->table = $table;
        $this->count = $count;
        $this->databaseIdentifier = $databaseIdentifier;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    public function populate(): array
    {
        $this->emitEvent('beforeStart');
        $this->emitEvent('start');
        $items = [];
        for ($i = 0; $i < $this->count; ++$i) {
            $this->faker = $this->languages
                ? Factory::create($this->languages[array_rand($this->languages)])
                : Factory::create();
            $data = $this->generateData();
            $data = $this->postProcessData($data);
            $item = $this->getDatabase()->insert($this->table, $data);
            $items[] = $item;
            $this->emitEvent('progress');
        }
        $this->emitEvent('end');
        $this->emitEvent('afterEnd');
        return $items;
    }

    public function setDatabases(array $databases): PopulatorInterface
    {
        $this->databases = $databases;
        return $this;
    }

    public function addLanguage($language, $priority = 10): PopulatorInterface
    {
        $this->languages = array_merge($this->languages, array_fill(0, $priority, $language));
        return $this;
    }

    public function setLanguages(array $languages): PopulatorInterface
    {
        if ($this->languages) {
            return $this;
        }

        $this->languages = $languages;
        return $this;
    }

    public function addEvent(EventInterface $event): PopulatorInterface
    {
        $this->events[] = $event;
        return $this;
    }

    protected function postProcessData(array $data): array
    {
        return $data;
    }

    abstract protected function generateData(): array;

    protected final function getDatabase()
    {
        // TODO add parameter to this function - if foreign key has some other table etc.
        if ($this->database) {
            return $this->database;
        }
        if ($this->databaseIdentifier === null && count($this->databases) === 1) {
            $this->database = current($this->databases);
            return $this->database;
        }
        if ($this->databaseIdentifier !== null && isset($this->databases[$this->databaseIdentifier])) {
            $this->database = $this->databases[$this->databaseIdentifier];
            return $this->database;
        }
        throw new Exception('Database not found');
    }

    protected final function emitEvent(string $eventType)
    {
        foreach ($this->events as $event) {
            call_user_func([$event, $eventType]);
        }
    }
}
