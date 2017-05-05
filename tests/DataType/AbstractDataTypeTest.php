<?php

namespace Populator\Tests\Structure;

use Faker\Factory;
use PHPUnit\Framework\TestCase;

abstract class AbstractDataTypeTest extends TestCase
{
    protected $faker;

    protected function setUp()
    {
        $this->faker = Factory::create();
    }
}
