<?php

namespace Populator\Tests\Event;

use Populator\Event\EventInterface;
use Populator\Event\ProgressBarEvent;

class ProgressBarEventTest extends EventTest
{
    public function testEvents()
    {
        $event = new ProgressBarEvent();
        $input = $this->createInput();
        $output = $this->createOutput();
        $populator = $this->createPopulator('my_table', 10);
        $this->assertInstanceOf(EventInterface::class, $event->create($populator, $input, $output));

        $this->assertEmpty($output->getMessages());
        $event->beforeStart();
        $this->assertCount(0, $output->getMessages());
        $this->assertCount(0, $output->getMessages(0));

        $event->start();
        $this->assertCount(1, $output->getMessages());
        $this->assertCount(1, $output->getMessages(0));

        $event->progress();
        $this->assertCount(1, $output->getMessages());
        $this->assertCount(3, $output->getMessages(0));

        $event->end();
        $this->assertCount(1, $output->getMessages());
        $this->assertCount(6, $output->getMessages(0));

        $event->afterEnd();
        $this->assertCount(1, $output->getMessages());
        $this->assertCount(6, $output->getMessages(0));
    }
}
