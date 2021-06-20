<?php

namespace Populator\Event;

class SimpleInfoEvent extends AbstractEvent
{
    /** @var float */
    private $startTime;

    public function beforeStart(): void
    {
        $this->startTime = microtime(true);
        $this->output->writeln('<comment>Populating data to table "' . $this->populator->getTableName() . '":</comment>');
    }

    public function afterEnd(): void
    {
        $this->output->writeln('<comment>Done. Took ' . sprintf('%.4fs', microtime(true) - $this->startTime) . '</comment>');
        $this->output->writeln('');
    }
}
