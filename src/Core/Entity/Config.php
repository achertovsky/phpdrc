<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Entity;

class Config
{
    /**
     * @param array<string, array<string>> $coreNamespaces
     */
    public function __construct(
        private array $coreNamespaces,
        private array $excludes = []
    ) {
    }

    /**
     * @return string[]
     */
    public function getCoreNamespaces(): array
    {
        return array_keys($this->coreNamespaces);
    }

    /**
     * @return string[]
     */
    public function getNamespacesAllowedInCoreNamespace(string $namespace): array
    {
        return $this->coreNamespaces[$namespace];
    }

    /**
     * @return string[]
     */
    public function getExcludes(): array
    {
        return $this->excludes;
    }
}
