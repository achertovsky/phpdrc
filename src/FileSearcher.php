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
        // @todo: add ending slash check and directory starting slash
        $directoryIterator = new RecursiveDirectoryIterator($this->baseDirectory . $directory);
        $recursiveIterator = new RecursiveIteratorIterator($directoryIterator);
        $fileIterator = new RegexIterator($recursiveIterator, '#^'.$this->baseDirectory.'(.+\.php)$#i', RecursiveRegexIterator::GET_MATCH);
        //var_dump(iterator_to_array($fileIterator, false));

        $files = [];
        foreach ($fileIterator as $file) {
            $files[] = $file[1];
        }

        return $files;
    }
}
