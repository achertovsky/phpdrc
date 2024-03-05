<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

interface YamlParserInterface
{
    // @phpstan-ignore-next-line
    public function parse(string $path): array;
}
