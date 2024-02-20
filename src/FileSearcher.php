<?php

declare(strict_types=1);

namespace achertovsky\DRC;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class FileSearcher
{
    public function __construct(
        private string $baseDirectory
    ) {
    }

    public function search(string $directory): array
    {
        $directoryIterator = new RecursiveDirectoryIterator($this->baseDirectory . $this->getDirectoryPath($directory));
        $recursiveIterator = new RecursiveIteratorIterator($directoryIterator);
        $fileIterator = new RegexIterator($recursiveIterator, '#^'.$this->baseDirectory.'(.+\.php)$#i', RecursiveRegexIterator::GET_MATCH);

        $files = [];
        foreach ($fileIterator as $file) {
            $files[] = $file[1];
        }

        return $files;
    }

    private function getDirectoryPath(string $directory): string
    {
        return ltrim($directory, '/');
    }
}
