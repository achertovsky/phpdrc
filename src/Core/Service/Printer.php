<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use achertovsky\DRC\Core\Entity\Violation;

class Printer implements PrinterInterface
{
    public function print(Violation $violation): void
    {
        echo $violation->getFilePath() . ':' . PHP_EOL;
        foreach ($violation->getWrongUses() as $wrongUse) {
            echo $wrongUse . PHP_EOL;
        }
    }
}
