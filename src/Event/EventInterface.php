<?php

namespace Populator\Event;

use Populator\Populator\PopulatorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface EventInterface
{
    public function create(PopulatorInterface $populator, InputInterface $input, OutputInterface $output): EventInterface;

    public function beforeStart();

    public function start();

    public function progress();

    public function end();

    public function afterEnd();
}
