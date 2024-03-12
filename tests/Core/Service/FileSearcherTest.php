<?php

declare(strict_types=1);

namespace achertovsky\DRC\Tests\Core\Service;

use PHPUnit\Framework\TestCase;
use achertovsky\DRC\Core\Service\FileSearcher;

class FileSearcherTest extends TestCase
{
    private FileSearcher $fileSearcher;

    protected function setUp(): void
    {
        $this->fileSearcher = new FileSearcher(__DIR__ . '/');
    }

    protected function tearDown(): void
    {
    }

    public function testFindOnlyPhpFiles(): void
    {
        $filePaths = $this->fileSearcher->search('file_searcher_fixtures/');
        sort($filePaths);

        $this->assertEquals(
            [
                __DIR__ . '/file_searcher_fixtures/Directory1/file1.php',
                __DIR__ . '/file_searcher_fixtures/Directory1/file2.php',
                __DIR__ . '/file_searcher_fixtures/file3.php',
            ],
            $filePaths
        );
    }

    public function testWouldIgnoreSlashPrefixInPath(): void
    {
        $filePaths = $this->fileSearcher->search('/file_searcher_fixtures/');
        sort($filePaths);

        $this->assertEquals(
            [
                __DIR__ . '/file_searcher_fixtures/Directory1/file1.php',
                __DIR__ . '/file_searcher_fixtures/Directory1/file2.php',
                __DIR__ . '/file_searcher_fixtures/file3.php',
            ],
            $filePaths
        );
    }
}
