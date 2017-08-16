<?php

use Populator\Database\Database;
use Populator\Populator\AutomaticPopulator;
use Populator\Command\SimplePopulatorCommand;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$application = new Application();

$database = new Database('populator', 'mysql:dbname=populator;host=localhost', 'root', '123');
$populatorCommand = new SimplePopulatorCommand($database, 'ru_RU');

$table1Populator = new AutomaticPopulator('table_1', 10);
$populatorCommand->addPopulator($table1Populator);

$table2Populator = new AutomaticPopulator('table_2', 15);
$populatorCommand->addPopulator($table2Populator);

$application->add($populatorCommand);
$application->run();
