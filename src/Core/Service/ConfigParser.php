<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use LogicException;
use achertovsky\DRC\Core\Entity\Config;

class ConfigParser
{
    public function __construct(
        private YamlParserInterface $yamlParser,
    ) {
    }

    public function parse(string $path): Config
    {
        if (!file_exists($path)) {
            throw new LogicException('Config file not found');
        }

        $configArray = $this->yamlParser->parse($path);
        foreach ($configArray as $coreNamespace => $allowedNamespaces) {
            if (!is_string($coreNamespace)) {
                throw new LogicException('Namespace should be a string');
            }
            foreach ($allowedNamespaces as $allowedNamespace) {
                if (!is_string($allowedNamespace)) {
                    throw new LogicException('Namespace should be a string');
                }
            }
        }

        return new Config($configArray);
    }
}
