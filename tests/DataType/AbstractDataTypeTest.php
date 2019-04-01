<?php

namespace Populator\Tests\DataType;

use Faker\Factory;
use PHPUnit\Framework\TestCase;

abstract class AbstractDataTypeTest extends TestCase
{
    protected $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }
}
