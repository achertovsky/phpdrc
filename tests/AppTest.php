<?php

declare(strict_types=1);

namespace achertovsky\DRC\Tests;

use achertovsky\DRC\App;
use PHPUnit\Framework\TestCase;
use achertovsky\DRC\Core\Entity\Config;
use achertovsky\DRC\Core\Entity\Violation;
use achertovsky\DRC\Core\Service\FileParser;
use PHPUnit\Framework\MockObject\MockObject;
use achertovsky\DRC\Core\Service\ConfigParser;
use achertovsky\DRC\Core\Service\FileSearcher;
use achertovsky\DRC\Core\Entity\ParsedFileContent;
use achertovsky\DRC\Core\Service\PrinterInterface;
use achertovsky\DRC\Core\Service\ValidatorInterface;

class AppTest extends TestCase
{
    private const ROOT_DIR = 'dir123';
    private const CONFIG_FILE_PATH = 'config.yaml';

    private MockObject $configParserMock;
    private MockObject $fileSearcherMock;
    private MockObject $fileParserMock;
    private MockObject $validatorMock;
    private MockObject $printerMock;

    private App $app;

    protected function setUp(): void
    {
        $this->configParserMock = $this->createMock(ConfigParser::class);
        $this->fileSearcherMock = $this->createMock(FileSearcher::class);
        $this->fileParserMock = $this->createMock(FileParser::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->printerMock = $this->createMock(PrinterInterface::class);

        $this->app = new App(
            $this->configParserMock,
            $this->fileSearcherMock,
            $this->fileParserMock,
            $this->validatorMock,
            $this->printerMock
        );
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testRun(): void
    {
        $rootNamespace = 'achertovsky\DRC';
        $allowedNamespaces = [
            'achertovsky\DRC\Tests'
        ];
        $config = new Config(
            [
                $rootNamespace => $allowedNamespaces
            ]
        );

        $this->configParserMock
            ->expects($this->once())
            ->method('parse')
            ->with(self::CONFIG_FILE_PATH)
            ->willReturn($config)
        ;

        $file1 = 'file1.php';
        $file2 = 'file2.php';
        $filesList = [
            $file1,
            $file2,
        ];
        $this->fileSearcherMock
            ->expects($this->once())
            ->method('search')
            ->with(self::ROOT_DIR)
            ->willReturn($filesList)
        ;

        $parsedFileContent1 = new ParsedFileContent(
            $file1,
            'namespace1',
            [
                'entity1',
                'entity2'
            ]
        );
        $parsedFileContent2 = new ParsedFileContent(
            $file2,
            'namespace2',
            [
                'entity3',
                'entity4'
            ]
        );

        $this->fileParserMock
            ->expects($this->exactly(2))
            ->method('parse')
            ->willReturnMap(
                [
                    [
                        $file1,
                        $parsedFileContent1
                    ],
                    [
                        $file2,
                        $parsedFileContent2
                    ],
                ]
            )
        ;

        $violation1 = null;
        $violation2 = new Violation(
            $file2,
            'Namespace1',
            [
                'entity3',
            ]
        );

        $this->validatorMock
            ->expects($this->exactly(2))
            ->method('validate')
            ->willReturnMap(
                [
                    [
                        $parsedFileContent1,
                        $config,
                        $violation1
                    ],
                    [
                        $parsedFileContent2,
                        $config,
                        $violation2
                    ],
                ]
            )
        ;

        $this->printerMock
            ->expects($this->once())
            ->method('print')
            ->with($violation2)
        ;

        $this->assertEquals(
            App::HAS_VIOLATIONS,
            $this->app->run(
                self::ROOT_DIR,
                self::CONFIG_FILE_PATH
            )
        );
    }
}
