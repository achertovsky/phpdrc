<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use achertovsky\DRC\Core\Entity\ParsedFileContent;
use achertovsky\DRC\Core\Entity\Violation;
use achertovsky\DRC\Core\Entity\Config;

interface ValidatorInterface
{
    public function getViolation(
        ParsedFileContent $fileContent,
        Config $config
    ): ?Violation;
}
