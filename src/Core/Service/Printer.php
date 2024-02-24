<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use achertovsky\DRC\Core\Entity\Violation;

class Printer implements PrinterInterface
{
    /**
     * @param Violation[] $violations
     */
    public function print(array $violations): void
    {
        foreach ($violations as $violation) {
            echo $violation->getFilePath() . ':' . PHP_EOL;
            foreach ($violation->getWrongUses() as $wrongUse) {
                echo $wrongUse . PHP_EOL;
            }
        }
    }
}
