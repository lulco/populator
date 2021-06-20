<?php

namespace Populator\Populator;

use Exception;
use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use PDOException;
use Populator\Database\DatabaseInterface;
use Populator\Event\EventInterface;

abstract class AbstractPopulator implements PopulatorInterface
{
    /** @var DatabaseInterface[] */
    protected $databases = [];

    /** @var array */
    protected $languages = [];

    /** @var EventInterface[] */
    protected $events = [];

    /** @var string */
    protected $table;

    /** @var int */
    protected $count;

    /** @var string|null */
    protected $databaseIdentifier;

    /** @var Generator[] */
    private $fakers = [];

    /** @var int */
    private $maxRetries = 3;

    /** @var int */
    private $retries = 0;

    public function __construct(string $table, int $count = 10, ?string $databaseIdentifier = null)
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

    public function populate(): int
    {
        $this->emitEvent('beforeStart');
        $this->emitEvent('start');
        $inserted = 0;
        for ($i = 0; $i < $this->count; ++$i) {
            $data = $this->generateData($this->getFaker());
            $data = $this->postProcessData($data);
            try {
                $this->getDatabase()->insert($this->table, $data) ? $inserted++ : null;
            } catch (PDOException $e) {
                $i--;
                if ($this->retries === $this->maxRetries) {
                    break;
                }
                $this->retries++;
                continue;
            }
            $this->retries = 0;
            $this->emitEvent('progress');
        }
        $this->emitEvent('end');
        $this->emitEvent('afterEnd');
        return $inserted;
    }

    public function setDatabases(array $databases): PopulatorInterface
    {
        if (empty($databases)) {
            throw new InvalidArgumentException('Databases cannot be empty');
        }
        foreach ($databases as $database) {
            if (!$database instanceof DatabaseInterface) {
                throw new InvalidArgumentException('All databases must be instance of DatabaseInterface');
            }
        }
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
        if (!empty($this->languages)) {
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

    abstract protected function generateData(Generator $faker): array;

    final protected function getDatabase(?string $database = null): DatabaseInterface
    {
        $databaseIdentifier = $database ?: $this->databaseIdentifier;
        if (!$databaseIdentifier) {
            $currentDatabase = current($this->databases);
            if (!$currentDatabase) {
                throw new Exception('No current database found');
            }
            return $currentDatabase;
        }
        if (!isset($this->databases[$databaseIdentifier])) {
            throw new Exception('Datbase not found');
        }
        return $this->databases[$databaseIdentifier];
    }

    final protected function emitEvent(string $eventType): void
    {
        foreach ($this->events as $event) {
            $callback =[$event, $eventType];
            if (is_callable($callback)) {
                call_user_func($callback);
            }
        }
    }

    private function getFaker(): Generator
    {
        $languages = $this->languages;
        $language = $languages
            ? $languages[array_rand($languages)]
            : Factory::DEFAULT_LOCALE;

        if (!isset($this->fakers[$language])) {
            $this->fakers[$language] = Factory::create($language);
        }

        return $this->fakers[$language];
    }
}
