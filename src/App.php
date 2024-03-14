<?php

declare(strict_types=1);

namespace achertovsky\DRC;

use achertovsky\DRC\Core\Service\FileParser;
use achertovsky\DRC\Core\Service\ConfigParser;
use achertovsky\DRC\Core\Service\FileSearcher;
use achertovsky\DRC\Core\Service\PrinterInterface;
use achertovsky\DRC\Core\Service\ValidatorInterface;

class App
{
    public const NO_VIOLATIONS = 0;
    public const HAS_VIOLATIONS = 1;

    public function __construct(
        private ConfigParser $configParser,
        private FileSearcher $fileSearcher,
        private FileParser $fileParser,
        private ValidatorInterface $validator,
        private PrinterInterface $printer
    ) {
    }

    public function run(
        string $rootDir,
        string $configPath
    ): int {
        $violationsStatus = self::NO_VIOLATIONS;
        $config = $this->configParser->parse($configPath);
        $filesList = $this->fileSearcher->search($rootDir);
        foreach ($filesList as $file) {
            $parsedFileContent = $this->fileParser->parse($file);
            $violation = $this->validator->validate(
                $parsedFileContent,
                $config
            );
            if ($violation === null) {
                continue;
            }

            $violationsStatus = self::HAS_VIOLATIONS;

            $this->printer->print($violation);
        }

        return $violationsStatus;
    }
}
