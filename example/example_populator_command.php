<?php

use Populator\Event\ProgressBarEvent;
use Populator\Event\SimpleInfoEvent;
use Populator\Populator\AutomaticPopulator;
use Populator\Command\PopulatorCommand;
use Symfony\Component\Console\Application;
use Populator\Database\Database;

require_once __DIR__ . '/../vendor/autoload.php';

$application = new Application();

$populator = new PopulatorCommand();
// database setup
$populator->addDatabase(new Database('populator', 'mysql:dbname=populator;host=localhost', 'root', '123'));
// languages setup
$populator->addLanguage('sk_SK');
$populator->addLanguage('en_US', 5);
// events setup
$populator->addEvent(new SimpleInfoEvent());
$populator->addEvent(new ProgressBarEvent());

$table1Populator = new AutomaticPopulator('table_1', 10);
$populator->addPopulator($table1Populator);

$table2Populator = new AutomaticPopulator('table_2', 15);
$populator->addPopulator($table2Populator);

$table3Populator = new AutomaticPopulator('table_3', 15);
// reset language for this table populator
$table3Populator->addLanguage('cs_CZ', 3);
$populator->addPopulator($table3Populator);

$application->add($populator);
$application->run();
