<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Entity;

class ParsedFileContent
{
    public function __construct(
        private string $filePath,
        private string $namespace,
        private array $uses
    ) {}

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getUses(): array
    {
        return $this->uses;
    }
}
