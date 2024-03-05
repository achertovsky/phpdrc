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
    ): void {
        $config = $this->configParser->parse($configPath);

        $filesList = $this->fileSearcher->search(
            $rootDir,
            $config
        );
        foreach ($filesList as $file) {
            $parsedFileContent = $this->fileParser->parse($file);
            $violation = $this->validator->validate(
                $parsedFileContent,
                $config
            );
            if ($violation === null) {
                continue;
            }

            $this->printer->print($violation);
        }
    }
}
