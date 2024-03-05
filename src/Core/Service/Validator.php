<?php

declare(strict_types=1);

namespace achertovsky\DRC\Core\Service;

use achertovsky\DRC\Core\Entity\ParsedFileContent;
use achertovsky\DRC\Core\Entity\Violation;
use achertovsky\DRC\Core\Entity\Config;

class Validator implements ValidatorInterface
{
    public function validate(
        ParsedFileContent $fileContent,
        Config $config
    ): ?Violation {
        $coreNamespaces = $config->getCoreNamespaces();
        $isCoreNamespace = false;
        foreach ($coreNamespaces as $coreNamespace) {
            if (strpos($fileContent->getNamespace(), $coreNamespace) === 0) {
                $isCoreNamespace = true;
                break;
            }
        }

        if (!$isCoreNamespace) {
            return null;
        }

        $allowedNamespaces = array_merge(
            [$fileContent->getNamespace() . '\\'],
            $config->getNamespacesAllowedInCoreNamespace($fileContent->getNamespace())
        );

        $violatedUses = [];
        foreach ($fileContent->getUses() as $use) {
            foreach ($allowedNamespaces as $allowedNamespace) {
                if (strpos($use, $allowedNamespace) === 0) {
                    continue 2;
                }
            }

            $violatedUses[] = $use;
        }

        if (count($violatedUses) > 0) {
            return new Violation(
                $fileContent->getFilePath(),
                $violatedUses
            );
        }

        return null;
    }
}
