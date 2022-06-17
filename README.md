# Populator
Allows populate fake data to your database

[![PHP unit](https://github.com/lulco/populator/workflows/PHPunit/badge.svg)](https://github.com/lulco/populator/actions?query=workflow%3APHPunit)
[![PHPStan level](https://img.shields.io/badge/PHPStan-level:%205-brightgreen.svg)](https://github.com/lulco/populator/actions?query=workflow%3A"PHP+static+analysis")
[![PHP static analysis](https://github.com/lulco/populator/workflows/PHP%20static%20analysis/badge.svg)](https://github.com/lulco/populator/actions?query=workflow%3A"PHP+static+analysis")
[![SensioLabsInsight](https://insight.symfony.com/projects/1795bc5d-8063-4c6b-ab34-13c9c614216e/mini.png)](https://insight.sensiolabs.com/projects/1795bc5d-8063-4c6b-ab34-13c9c614216e)
[![Latest Stable Version](https://img.shields.io/packagist/v/lulco/populator.svg)](https://packagist.org/packages/lulco/populator)
[![Total Downloads](https://img.shields.io/packagist/dt/lulco/populator.svg?style=flat-square)](https://packagist.org/packages/lulco/populator)
[![PHP 7 supported](http://php7ready.timesplinter.ch/lulco/populator/master/badge.svg)](https://travis-ci.org/lulco/populator)

The best feature of this library is AutomaticPopulatorCommand which populates data based on full database structure. It analyses database table tree and creates items for each table - leaves first.

```php
// file bin/command.php

$database = new Populator\Database\Database('db_name', 'mysql:dbname=db_name;host=db_host', 'db_user', 'db_password');
$populator = new Populator\Command\AutomaticPopulatorCommand($database, ['phoenix_log', 'phinxlog', 'migration_log', 'versions', 'api_logs', 'api_tokens'], true, $columnNameAndTypeCallbacks);

$application = new Application();
$application->add($populator);
$application->run();
```

Run:
```
php bin/command.php populator:populate
````

With this setup, AutomaticPopulatorCommand will create 5 items in all leaf-tables, than 25 for 2nd level, and 125 for all next levels. It can be changed by parameters `$countBase` and `$maxCountPerTable` of AutomaticPopulatorCommand.  



You can also use AutomaticPopulator which allows you to create fake data for one table based on its structure or column names.

Create file e.g. `bin/command.php` as shown below (or add PopulatorCommand to existing Symfony console application):

```php
// file bin/command.php

$application = new Symfony\Component\Console\Application();
$populator = new Populator\Command\PopulatorCommand();
$populator->addDatabase(new Populator\Database\Database('db_name', 'mysql:dbname=db_name;host=db_host', 'db_user', 'db_password'));

$table1Populator = new Populator\Populator\AutomaticPopulator('table_1', 50);
$populator->addPopulator($table1Populator);

$application->add($populator);
$application->run();
```

This setup will populate 50 fake rows for database table with name `table_1` after executing this command:
```
php bin/command.php populator:populate
````
