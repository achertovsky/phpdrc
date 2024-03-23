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
    public function search(
        string $directory,
        array $exclude = []
    ): array {
        $baseDirectory = rtrim($this->baseDirectory, '/') . '/';
        $excludedPaths = array_map(
            fn (string $file) => $baseDirectory . $file,
            $exclude
        );
        $directoryIterator = new RecursiveDirectoryIterator($baseDirectory . $this->getDirectoryPath($directory));
        $recursiveIterator = new RecursiveIteratorIterator($directoryIterator);
        $fileIterator = new RegexIterator(
            $recursiveIterator,
            '#^(.+\.php)$#i',
            RecursiveRegexIterator::GET_MATCH
        );

        $files = [];
        foreach ($fileIterator as $file) {
            foreach ($excludedPaths as $excludedPath) {
                if (strpos($file[1], $excludedPath) === 0) {
                    continue 2;
                }
            }
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
