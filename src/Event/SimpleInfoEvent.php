<?php

namespace Populator\Event;

class SimpleInfoEvent extends AbstractEvent
{
    private $startTime;

    public function beforeStart()
    {
        $this->startTime = microtime(true);
        $this->output->writeln('<comment>Populating data to table "' . $this->populator->getTableName() . '":</comment>');
    }

    public function afterEnd()
    {
        $this->output->writeln('<comment>Done. Took ' . sprintf('%.4fs', microtime(true) - $this->startTime) . '</comment>');
        $this->output->writeln('');
    }
}
