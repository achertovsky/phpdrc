<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use achertovsky\DRC\Core\Entity\ParsedFileContent;

class FileParser
{
    private const USE_BLOCK = 'use ';
    private const NAMESPACE_BLOCK = 'namespace ';

    public function parse(string $path): ParsedFileContent
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        $namespace = '';
        $uses = [];

        /** @var string[] $lines */
        foreach ($lines as $line) {
            if (strpos($line, self::USE_BLOCK) === 0) {
                $uses[] = trim(substr($line, strlen(self::USE_BLOCK), -1));
            }
            if (strpos($line, self::NAMESPACE_BLOCK) === 0) {
                $namespace = trim(substr($line, strlen(self::NAMESPACE_BLOCK), -1));
            }
        }

        return new ParsedFileContent(
            $path,
            $namespace,
            $uses
        );
    }
}
