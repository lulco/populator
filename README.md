# Populator
Allows populate fake data to your database

[![PHP unit](https://github.com/lulco/populator/workflows/PHPunit/badge.svg)](https://github.com/lulco/populator/actions?query=workflow%3APHPunit)
[![PHPStan level](https://img.shields.io/badge/PHPStan-level:%205-brightgreen.svg)](https://github.com/lulco/populator/actions?query=workflow%3A"PHP+static+analysis")
[![PHP static analysis](https://github.com/lulco/populator/workflows/PHP%20static%20analysis/badge.svg)](https://github.com/lulco/populator/actions?query=workflow%3A"PHP+static+analysis")
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1795bc5d-8063-4c6b-ab34-13c9c614216e/mini.png)](https://insight.sensiolabs.com/projects/1795bc5d-8063-4c6b-ab34-13c9c614216e)
[![Latest Stable Version](https://img.shields.io/packagist/v/lulco/populator.svg)](https://packagist.org/packages/lulco/populator)
[![Total Downloads](https://img.shields.io/packagist/dt/lulco/populator.svg?style=flat-square)](https://packagist.org/packages/lulco/populator)
[![PHP 7 supported](http://php7ready.timesplinter.ch/lulco/populator/master/badge.svg)](https://travis-ci.org/lulco/populator)

The best feature of this library is AutomaticPopulator which allows you to create fake data for one table based on its structure or column names.

Create file e.g. `bin/command.php` as shown below (or add PopulatorCommand to existing Symfony console application):

```php
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
