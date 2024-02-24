<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Entity;

class Violation
{
    public function __construct(
        private string $filePath,
        private array $wrongUses
    ) {}

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string[]
     */
    public function getWrongUses(): array
    {
        return $this->wrongUses;
    }
}
