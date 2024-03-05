<?php

declare(strict_types=1);

namespace achertovsky\DRC\Bridge;

use Symfony\Component\Yaml\Yaml;
use achertovsky\DRC\Core\Service\YamlParserInterface;

class YamlParser implements YamlParserInterface
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // @phpstan-ignore-next-line
    public function parse(string $path): array
    {
        // @phpstan-ignore-next-line
        return Yaml::parseFile($path);
    }
}
