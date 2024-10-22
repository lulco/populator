<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

return new class implements InputInterface {
    public function bind(InputDefinition $definition): void
    {

    }

    public function getArgument($name): mixed
    {
        return null;
    }

    public function getArguments(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getFirstArgument(): ?string
    {
        return null;
    }

    /**
     * @return string|bool|int|float|array|null
     */
    public function getOption($name): mixed
    {
        return null;
    }

    /**
     * @return array<string, string|bool|int|float|array|null>
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * @return string|bool|int|float|array|null
     */
    public function getParameterOption($values, $default = false, $onlyParams = false): mixed
    {
        return null;
    }

    public function hasArgument($name): bool
    {
        return false;
    }

    public function hasOption($name): bool
    {
        return false;
    }

    public function hasParameterOption($values, $onlyParams = false): bool
    {
        return false;
    }

    public function isInteractive(): bool
    {
        return false;
    }

    /**
     * @param string|array<string, string|bool|int|float|array|null> $value
     */
    public function setArgument($name, $value): void
    {
    }

    /**
     * @param string|bool|int|float|array|null $value
     */
    public function setInteractive($interactive): void
    {
    }

    /**
     * @param string|bool|int|float|array|null $value
     */
    public function setOption($name, $value): void
    {
    }

    public function validate(): void
    {
    }

    public function __toString(): string
    {
        return '';
    }
};
