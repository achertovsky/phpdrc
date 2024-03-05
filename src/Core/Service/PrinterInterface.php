<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use achertovsky\DRC\Core\Entity\Violation;

interface PrinterInterface
{
    public function print(Violation $violation): void;
}
