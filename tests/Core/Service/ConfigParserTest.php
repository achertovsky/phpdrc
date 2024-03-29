<?php

declare(strict_types=1);

namespace achertovsky\DRC\Tests\Core\Service;

use LogicException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use achertovsky\DRC\Core\Service\ConfigParser;
use PHPUnit\Framework\Attributes\DataProvider;
use achertovsky\DRC\Core\Service\YamlParserInterface;

class ConfigParserTest extends TestCase
{
    private MockObject $yamlParser;

    private ConfigParser $parser;

    protected function setUp(): void
    {
        $this->yamlParser = $this->createMock(YamlParserInterface::class);
        $this->parser = new ConfigParser(
            $this->yamlParser
        );
    }

    /**
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    // @phpstan-ignore-next-lines
    #[DataProvider('dataParse')]
    public function testParse(
        array $yamlParsedContent,
        array $expectedCoreNamespaces,
        array $expectedNamespacesAllowedInCoreNamespace,
        array $expectedExclude = []
    ): void {
        $path = __FILE__;

        $this->yamlParser
            ->method('parse')
            ->with($path)
            ->willReturn($yamlParsedContent)
        ;

        $config = $this->parser->parse($path);

        $this->assertEquals(
            $expectedCoreNamespaces,
            $config->getCoreNamespaces()
        );

        $this->assertEquals(
            $expectedNamespacesAllowedInCoreNamespace,
            $config->getNamespacesAllowedInCoreNamespace('achertovsky\DRC')
        );

        $this->assertEquals(
            $expectedExclude,
            $config->getExcludes()
        );
    }

    // @phpstan-ignore-next-line
    public static function dataParse(): array
    {
        return [
            'core namespaces with array' => [
                [
                    'namespaces' => [
                        'achertovsky\DRC' => [
                            'PHPUnit\Framework',
                        ],
                    ],
                ],
                [
                    'achertovsky\DRC',
                ],
                [
                    'PHPUnit\Framework',
                ],
            ],
            'core namespace has no allowed namespaces configured' => [
                [
                    'namespaces' => [
                        'achertovsky\DRC' => [],
                    ],
                    'exclude' => [
                        'whatever/path',
                    ]
                ],
                [
                    'achertovsky\DRC',
                ],
                [
                ],
                [
                    'whatever/path',
                ],
            ],
        ];
    }

    // @phpstan-ignore-next-line
    #[DataProvider('dataWouldThrowException')]
    public function testWouldThrowException(
        array $yamlParsedContent
    ): void {
        $this->yamlParser
            ->method('parse')
            ->willReturn($yamlParsedContent)
        ;

        $this->expectException(LogicException::class);
        $this->parser->parse(__FILE__);
    }

    // @phpstan-ignore-next-line
    public static function dataWouldThrowException(): array
    {
        return [
            'core namespace is not a string' => [
                [
                    'namespaces' => [
                        1 => [],
                    ],
                ],
            ],
            'allowed namespace is not a string' => [
                [
                    'namespaces' => [
                        'achertovsky\DRC' => [
                            1,
                        ],
                    ],
                ],
            ],
            'no namespaces key' => [
                [
                    'no-namespaces' => [],
                ],
            ],
            'exclude has no ' => [
                [
                    'namespaces' => [
                        '' => [],
                    ],
                    'exclude' => [
                        1,
                    ],
                ],
            ],
        ];
    }

    public function testIssueParseWontWorkAsNoFileExist(): void
    {
        $this->expectException(LogicException::class);

        $this->parser->parse('non-existent-file');
    }
}
