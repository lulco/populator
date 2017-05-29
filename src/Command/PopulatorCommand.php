<?php

namespace Populator\Command;

use Exception;
use Populator\Database\DatabaseInterface;
use Populator\Event\EventInterface;
use Populator\Populator\PopulatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulatorCommand extends Command
{
    /** @var DatabaseInterface[] */
    protected $databases = [];

    /** @var PopulatorInterface[] */
    protected $populators = [];

    /** @var array */
    protected $languages = [];

    /** @var EventInterface[] */
    protected $events = [];

    protected function configure()
    {
        $this->setName('populator:populate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->populators as $populator) {
            $populator->setLanguages($this->languages);
            $populator->setDatabases($this->databases);
            foreach ($this->events as $event) {
                $populator->addEvent($event->create($populator, $input, $output));
            }
            $populator->populate();
        }
    }

    public function addDatabase(DatabaseInterface $database): PopulatorCommand
    {
        $name = $database->getName();
        if (isset($this->databases[$name])) {
            throw new Exception('Database with name "' . $name . '" already exists');
        }
        $this->databases[$name] = $database;
        return $this;
    }

    public function addPopulator(PopulatorInterface $populator): PopulatorCommand
    {
        $this->populators[] = $populator;
        return $this;
    }

    public function addLanguage(string $language, int $priority = 10): PopulatorCommand
    {
        $this->languages = array_merge($this->languages, array_fill(0, $priority, $language));
        return $this;
    }

    public function addEvent(EventInterface $event): PopulatorCommand
    {
        $this->events[] = $event;
        return $this;
    }
}
