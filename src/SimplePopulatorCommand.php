<?php

namespace Populator;

use Exception;
use Faker\Factory;
use Populator\Database\DatabaseInterface;
use Populator\Event\EventInterface;
use Populator\Event\ProgressBarEvent;
use Populator\Event\SimpleInfoEvent;

class SimplePopulatorCommand extends PopulatorCommand
{
    public function __construct(DatabaseInterface $database, string $language = Factory::DEFAULT_LOCALE)
    {
        parent::__construct();
        parent::addLanguage($language);
        parent::addDatabase($database);
        parent::addEvent(new SimpleInfoEvent());
        parent::addEvent(new ProgressBarEvent());
    }

    public function addDatabase(DatabaseInterface $database): PopulatorCommand
    {
        throw new Exception('Only one database is available in simple populator');
    }

    public function addLanguage(string $language, int $priority = 10): PopulatorCommand
    {
        throw new Exception('Only one language is available in simple populator');
    }

    public function addEvent(EventInterface $event): PopulatorCommand
    {
        throw new Exception('Only predefined events are available in simple populator');
    }
}
