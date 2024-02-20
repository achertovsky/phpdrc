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

    #[DataProvider('dataParse')]
    public function testParse(
        array $yamlParsedContent,
        array $expectedCoreNamespaces,
        array $expectedNamespacesAllowedInCoreNamespace
    ): void {
        $path = '';

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
    }

    public static function dataParse(): array
    {
        return [
            'core namespaces with array' => [
                [
                    'achertovsky\DRC' => [
                        'PHPUnit\Framework',
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
                    'achertovsky\DRC' => [],
                ],
                [
                    'achertovsky\DRC',
                ],
                [
                ],
            ],
        ];
    }

    #[DataProvider('dataWouldThrowException')]
    public function testWouldThrowException(
        array $yamlParsedContent
    ): void {
        $this->yamlParser
            ->method('parse')
            ->willReturn($yamlParsedContent)
        ;

        $this->expectException(LogicException::class);
        $this->parser->parse('');
    }

    public static function dataWouldThrowException(): array
    {
        return [
            'core namespace is not a string' => [
                [
                    1 => [],
                ],
            ],
            'allowed namespace is not a string' => [
                [
                    'achertovsky\DRC' => [
                        1,
                    ],
                ],
            ],
        ];
    }
}
