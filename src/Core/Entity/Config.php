<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Entity;

class Config
{
    /**
     * @var array<string, string[]>
     */
    private array $coreNamespaces;

    public function __construct(
        array $coreNamespaces
    ) {
        $this->coreNamespaces = $coreNamespaces;
    }

    public function getCoreNamespaces(): array
    {
        return array_keys($this->coreNamespaces);
    }

    public function isCoreNamespace(string $namespace): bool
    {
        return in_array($namespace, $this->coreNamespaces, true);
    }

    public function getNamespacesAllowedInCoreNamespace(string $namespace): array
    {
        return $this->coreNamespaces[$namespace];
    }
}
