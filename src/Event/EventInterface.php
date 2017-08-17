<?php

namespace Populator\Event;

use Populator\Populator\PopulatorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface EventInterface
{
    public function create(PopulatorInterface $populator, InputInterface $input, OutputInterface $output): EventInterface;

    public function beforeStart(): void;

    public function start(): void;

    public function progress(): void;

    public function end(): void;

    public function afterEnd(): void;
}
