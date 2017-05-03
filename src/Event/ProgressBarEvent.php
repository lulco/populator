<?php

namespace Populator\Event;

use Populator\Populator\PopulatorInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressBarEvent extends AbstractEvent
{
    private $progressBar;

    public function create(PopulatorInterface $populator, InputInterface $input, OutputInterface $output): EventInterface
    {
        parent::create($populator, $input, $output);
        $this->progressBar = new ProgressBar($output, $populator->getCount());
        return $this;
    }

    public function start()
    {
        $this->progressBar->start();
    }

    public function progress()
    {
        $this->progressBar->advance();
    }

    public function end()
    {
        $this->progressBar->finish();
        $this->output->writeln('');
    }
}
