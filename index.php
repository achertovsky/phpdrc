<?php

declare(strict_types=1);

use achertovsky\DRC\App;
use achertovsky\DRC\Bridge\YamlParser;
use achertovsky\DRC\Core\Service\Printer;
use Symfony\Component\Console\Application;
use achertovsky\DRC\Core\Service\Validator;
use achertovsky\DRC\Core\Service\FileParser;
use achertovsky\DRC\Core\Service\ConfigParser;
use achertovsky\DRC\Core\Service\FileSearcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;

require_once __DIR__ . '/vendor/autoload.php';

$application = new Application();
$application
    ->register('drc')
    ->addOption(
        'checkPath',
        'p',
        InputOption::VALUE_OPTIONAL
    )
    ->addOption(
        'configPath',
        'c',
        InputOption::VALUE_OPTIONAL
    )
    ->setCode(function (InputInterface $input): int {
        (new App(
            new ConfigParser(
                new YamlParser()
            ),
            new FileSearcher(__DIR__),
            new FileParser(),
            new Validator(),
            new Printer()
        ))->run(
            '' . ($input->getOption('checkPath') ?? __DIR__),
            '' . ($input->getOption('configPath') ?? __DIR__ . '/config.yaml')
        );

        return Command::SUCCESS;
    })
;

$application->run();
