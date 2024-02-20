<?php

declare(strict_types=1);

namespace achertovsky\DRC\Bridge;

use Symfony\Component\Yaml\Yaml;
use achertovsky\DRC\Core\Service\YamlParserInterface;

class YamlParser implements YamlParserInterface
{
    public function parse(string $path): array
    {
        return Yaml::parseFile($path);
    }
}
