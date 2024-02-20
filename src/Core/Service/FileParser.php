<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use achertovsky\DRC\Core\Entity\ParsedFileContent;

class FileParser
{
    /** @todo implement according to test expectations */
    public function parse(string $path): ParsedFileContent
    {
        return new ParsedFileContent(
            '',
            []
        );
    }
}
