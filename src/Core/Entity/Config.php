<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Entity;

class Config
{
    /**
     * @param array<string, array<string>> $coreNamespaces
     */
    public function __construct(
        private array $coreNamespaces
    ) {
        $this->coreNamespaces = $coreNamespaces;
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
}
