<?php

namespace Populator\Command;

use Faker\Factory;
use Populator\Database\DatabaseInterface;
use Populator\Event\ProgressBarEvent;
use Populator\Event\SimpleInfoEvent;

class SimplePopulatorCommand extends PopulatorCommand
{
    public function __construct(DatabaseInterface $database, string $language = Factory::DEFAULT_LOCALE)
    {
        parent::__construct();
        $this->addDatabase($database);
        $this->addLanguage($language);
        $this->addEvent(new SimpleInfoEvent());
        $this->addEvent(new ProgressBarEvent());
    }
}
