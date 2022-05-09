<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $pathToFile): array
{
    if (!file_exists($pathToFile)) {
        $pathToFile = __DIR__ . "/../tests/fixtures/{$pathToFile}";
    }
    $pathInfo = pathinfo($pathToFile);
    $extension = $pathInfo['extension'];
    switch ($extension) {
        case 'json':
            $parsedFile = json_decode(file_get_contents($pathToFile), true);
            break;
        case 'yaml':
        case 'yml':
            $parsedFile = Yaml::parseFile($pathToFile);
            break;
    }
    return $parsedFile;
}
