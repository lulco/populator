<?php

namespace Populator\Populator;

use Populator\Database\DatabaseInterface;
use Populator\Event\EventInterface;

interface PopulatorInterface
{
    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @return string
     */
    public function getTableName(): string;

    /**
     * @param DatabaseInterface[] $databases
     * @return PopulatorInterface
     */
    public function setDatabases(array $databases): PopulatorInterface;

    /**
     * @param string $language
     * @param int $priority
     * @return PopulatorInterface
     */
    public function addLanguage($language, $priority): PopulatorInterface;

    /**
     * @param array $languages
     * @return PopulatorInterface
     */
    public function setLanguages(array $languages): PopulatorInterface;

    /**
     * @param EventInterface $event
     * @return PopulatorInterface
     */
    public function addEvent(EventInterface $event): PopulatorInterface;

    /**
     * @return void
     */
    public function populate(): void;
}
