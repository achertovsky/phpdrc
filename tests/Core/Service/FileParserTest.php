<?php

declare(strict_types=1);

namespace achertovsky\DRC\Tests\Core\Service;

use PHPUnit\Framework\TestCase;
use achertovsky\DRC\Core\Service\FileParser;
use achertovsky\DRC\Core\Entity\ParsedFileContent;

class FileParserTest extends TestCase
{
    private FileParser $parser;

    protected function setUp(): void
    {
        $this->parser = new FileParser();
    }

    public function testParse(): void
    {
        $this->assertEquals(
            new ParsedFileContent(
                __FILE__,
                'achertovsky\DRC\Tests\Core\Service',
                [
                    'PHPUnit\Framework\TestCase',
                    'achertovsky\DRC\Core\Service\FileParser',
                    'achertovsky\DRC\Core\Entity\ParsedFileContent',
                ]
            ),
            $this->parser->parse(__FILE__)
        );
    }
}
