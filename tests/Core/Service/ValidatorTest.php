<?php

declare(strict_types=1);

namespace achertovsky\DRC\Tests\Core\Service;

use PHPUnit\Framework\TestCase;
use achertovsky\DRC\Core\Entity\Config;
use achertovsky\DRC\Core\Entity\Violation;
use achertovsky\DRC\Core\Service\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use achertovsky\DRC\Core\Entity\ParsedFileContent;

class ValidatorTest extends TestCase
{
    private const FILE_PATH = 'directory/file.php';
    private const NAMESPACE_SERVICE = 'App\Service';
    private const NAMESPACE_ROOT = 'App';
    private const NAMESPACE_THIRD_PARTY = 'Third\Party';
    private const CLASS_ENTITY = 'App\Entity\Class';
    private const CLASS_SERVICE = 'App\Service\Class';
    private const CLASS_THIRD_PARTY = 'Third\Party\Class';

    private Validator $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
    }

    #[DataProvider('dataTestFileValidation')]
    public function testFileValidation(
        ?Violation $expectedViolation,
        ParsedFileContent $parsedFileContent,
        Config $config
    ): void {
        $this->assertEquals(
            $expectedViolation,
            $this->validator->validate(
                $parsedFileContent,
                $config
            )
        );
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    // @phpstan-ignore-next-line
    public static function dataTestFileValidation(): array
    {
        return [
            'namespace not covered by config' => [
                null,
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    []
                ),
                new Config([])
            ],
            'parsed file does not match config' => [
                null,
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    [
                        self::CLASS_ENTITY,
                        self::CLASS_SERVICE
                    ]
                ),
                new Config([
                    self::NAMESPACE_THIRD_PARTY => [],
                ])
            ],
            'valid namespace with no uses' => [
                null,
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    []
                ),
                new Config([
                    self::NAMESPACE_SERVICE => [],
                ])
            ],
            'valid namespace with allowed uses' => [
                null,
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    [
                        self::CLASS_ENTITY,
                        self::CLASS_SERVICE
                    ]
                ),
                new Config([
                    self::NAMESPACE_SERVICE => [
                        self::CLASS_ENTITY,
                        self::CLASS_SERVICE
                    ]
                ])
            ],
            'valid namespace with allowed partial use of self' => [
                null,
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    [
                        self::CLASS_SERVICE
                    ]
                ),
                new Config([
                    self::NAMESPACE_SERVICE => []
                ])
            ],
            'partial valid namespace' => [
                null,
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    [
                        self::CLASS_ENTITY
                    ]
                ),
                new Config([
                    self::NAMESPACE_ROOT => [
                        self::CLASS_ENTITY
                    ]
                ])
            ],
            'root namespace allows uses which derivates from it' => [
                null,
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    [
                        self::CLASS_ENTITY
                    ]
                ),
                new Config([
                    self::NAMESPACE_ROOT => []
                ])
            ],
            'valid namespace with allowed partial use of another namespace' => [
                null,
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    [
                        self::CLASS_THIRD_PARTY
                    ]
                ),
                new Config([
                    self::NAMESPACE_SERVICE => [
                        self::NAMESPACE_THIRD_PARTY,
                    ]
                ])
            ],
            'violation with non whitelisted third party class' => [
                new Violation(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    [
                        self::CLASS_THIRD_PARTY,
                    ]
                ),
                new ParsedFileContent(
                    self::FILE_PATH,
                    self::NAMESPACE_SERVICE,
                    [
                        self::CLASS_THIRD_PARTY
                    ]
                ),
                new Config([
                    self::NAMESPACE_SERVICE => []
                ])
            ],
        ];
    }
}
