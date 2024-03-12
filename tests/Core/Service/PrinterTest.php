<?php

declare(strict_types=1);

namespace achertovsky\DRC\Tests\Core\Service;

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

    public function testPrint(): void
    {
        $violation = new Violation(
            'file.php',
            'App1',
            [
                'use entity1',
                'use entity2'
            ]
        );
        ob_start();
        $this->printer->print($violation);
        $output = ob_get_clean();

        $this->assertEquals(
            'file.php (App1):' . PHP_EOL
            . 'use entity1' . PHP_EOL
            . 'use entity2' . PHP_EOL
            . PHP_EOL,
            $output
        );
    }
}
