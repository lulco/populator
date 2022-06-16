<?php

namespace Populator\Populator;

use Populator\Database\DatabaseInterface;
use Populator\Event\EventInterface;

interface PopulatorInterface
{
    public function getCount(): int;

    public function getTableName(): string;

    /**
     * @param DatabaseInterface[] $databases
     */
    public function setDatabases(array $databases): PopulatorInterface;

    public function addLanguage(string $language, int $priority): PopulatorInterface;

    /**
     * @param string[] $languages
     */
    public function setLanguages(array $languages): PopulatorInterface;

    public function addEvent(EventInterface $event): PopulatorInterface;

    public function populate(): int;
}
