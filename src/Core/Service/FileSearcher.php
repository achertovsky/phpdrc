<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use RegexIterator;
use RecursiveRegexIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class FileSearcher
{
    public function __construct(
        private string $baseDirectory
    ) {
    }

    /**
     * @return string[]
     */
    public function search(string $directory): array
    {
        $directoryIterator = new RecursiveDirectoryIterator($this->baseDirectory . $this->getDirectoryPath($directory));
        $recursiveIterator = new RecursiveIteratorIterator($directoryIterator);
        $fileIterator = new RegexIterator(
            $recursiveIterator,
            '#^' . $this->baseDirectory . '(.+\.php)$#i',
            RecursiveRegexIterator::GET_MATCH
        );

        $files = [];
        foreach ($fileIterator as $file) {
            /** @var array<int, string> $file */
            $files[] = $file[1];
        }

        return $files;
    }

    private function getDirectoryPath(string $directory): string
    {
        return ltrim($directory, '/');
    }
}
