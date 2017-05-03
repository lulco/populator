<?php

namespace Populator\Event;

use Populator\Populator\PopulatorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractEvent implements EventInterface
{
    protected $populator;

    /** @var InputInterface */
    protected $input;

    /** @var OutputInterface */
    protected $output;

    public function create(PopulatorInterface $populator, InputInterface $input, OutputInterface $output): EventInterface
    {
        $this->populator = $populator;
        $this->input = $input;
        $this->output = $output;
        return $this;
    }

    public function beforeStart()
    {
    }

    public function start()
    {
    }

    public function progress()
    {
    }

    public function end()
    {
    }

    public function afterEnd()
    {
    }
}
