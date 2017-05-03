<?php

namespace Populator\Structure;

class Column
{
    private $name;

    private $type;

    private $settings = [];

    public function __construct(string $name, string $type, array $settings = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->settings = $settings;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isAutoincrement(): bool
    {
        return isset($this->settings['autoincrement']) && $this->settings['autoincrement'] === true;
    }

    public function isNullable(): bool
    {
        return isset($this->settings['nullable']) && $this->settings['nullable'] == true;
    }

    public function getDefault(): ?string
    {
        return $this->settings['default'] ?? null;
    }

    public function getLength(): ?int
    {
        return $this->settings['length'] ?? null;
    }

    public function getDecimals(): ?int
    {
        return $this->settings['decimals'] ?? null;
    }

    public function isUnsigned(): bool
    {
        return $this->settings['unsigned'] ?? false;
    }

    public function getValues(): ?array
    {
        return $this->settings['values'] ?? null;
    }
}
