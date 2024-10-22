<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

return new class implements InputInterface {
    public function bind(InputDefinition $definition)
    {

    }

    public function getArgument($name)
    {

    }

    public function getArguments(): array
    {

    }

    public function getFirstArgument(): string
    {

    }

    public function getOption($name)
    {

    }

    public function getOptions(): array
    {

    }

    public function getParameterOption($values, $default = false, $onlyParams = false)
    {

    }

    public function hasArgument($name): bool
    {

    }

    public function hasOption($name): bool
    {

    }

    public function hasParameterOption($values, $onlyParams = false): bool
    {

    }

    public function isInteractive(): bool
    {

    }

    public function setArgument($name, $value)
    {

    }

    public function setInteractive($interactive)
    {

    }

    public function setOption($name, $value)
    {

    }

    public function validate()
    {

    }
};
