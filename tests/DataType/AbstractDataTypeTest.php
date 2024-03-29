<?php

namespace Populator\Tests\DataType;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

abstract class AbstractDataTypeTest extends TestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }
}
