<?php

declare(strict_types=1);

namespace achertovsky\DRC\Tests\Core\Service;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use achertovsky\DRC\Core\Service\Printer;
use achertovsky\DRC\Core\Entity\Violation;

class PrinterTest extends TestCase
{
    private Printer $printer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->printer = new Printer();
    }

    #[DataProvider('dataTestPrint')]
    public function testPrint(
        string $expectedOutput,
        array $violations
    ) {
        ob_start();
        $this->printer->print($violations);
        $output = ob_get_clean();

        $this->assertEquals(
            $expectedOutput,
            $output
        );
    }

    public static function dataTestPrint(): array
    {
        return [
            'no violations' => [
                '',
                []
            ],
            'one violation' => [
                'file.php:' . PHP_EOL
                . 'use entity1' . PHP_EOL
                . 'use entity2' . PHP_EOL,
                [
                    new Violation(
                        'file.php',
                        [
                            'use entity1',
                            'use entity2'
                        ]
                    )
                ]
            ]
        ];
    }
}
