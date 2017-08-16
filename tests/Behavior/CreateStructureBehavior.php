<?php

namespace Populator\Tests\Behavior;

use PDO;

trait CreateStructureBehavior
{
    private $pdoList = [];

    protected function cleanup()
    {
        $pdo = $this->getPdo();
        $database = getenv('POPULATOR_MYSQL_DATABASE');
        $charset = getenv('POPULATOR_MYSQL_CHARSET');
        $collation = getenv('POPULATOR_MYSQL_COLLATION');
        $pdo->query(sprintf('DROP DATABASE IF EXISTS `%s`;', $database));
        $pdo->query(sprintf('CREATE DATABASE `%s` CHARACTER SET %s COLLATE %s;', $database, $charset, $collation));
    }

    protected function createSimpleTable()
    {
        $pdo = $this->getPdo(getenv('POPULATOR_MYSQL_DATABASE'));
        $pdo->query(
"CREATE TABLE `simple` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('type1','type2','type3') DEFAULT 'type1',
  `sorting` int(10) unsigned DEFAULT NULL,
  `price` double(10,2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);"
        );
    }

    protected function createNoPrimaryKeysTable()
    {
        $pdo = $this->getPdo(getenv('POPULATOR_MYSQL_DATABASE'));
        $pdo->query(
"CREATE TABLE `no_primary_key` (
  `meta_key` varchar(255) NOT NULL,
  `meta_value` varchar(255) DEFAULT NULL
);"
        );
    }

    protected function createMultiplePrimaryKeysTable()
    {
        $pdo = $this->getPdo(getenv('POPULATOR_MYSQL_DATABASE'));
        $pdo->query(
"CREATE TABLE `multiple_primary_keys` (
  `pk1` int(11) NOT NULL,
  `pk2` char(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pk1`,`pk2`)
);"
        );
    }

    private function getPdo($database = null)
    {
        if (isset($this->pdoList[$database])) {
            return $this->pdoList[$database];
        }

        $dsn = 'mysql:host=' . getenv('POPULATOR_MYSQL_HOST');
        if ($database) {
            $dsn .= ';dbname=' . $database;
        }
        $pdo = new PDO($dsn, getenv('POPULATOR_MYSQL_USERNAME'), getenv('POPULATOR_MYSQL_PASSWORD'));
        $this->pdoList[$database] = $pdo;

        return $pdo;
    }
}
