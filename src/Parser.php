<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $pathToFile)
{
    if (!file_exists($pathToFile)) {
        $pathToFile = __DIR__ . "/../tests/fixtures/{$pathToFile}";
    }
    $pathInfo = pathinfo($pathToFile);
    $extension = $pathInfo['extension'];
    switch ($extension) {
        case 'json':
            $parsedFile = json_decode(file_get_contents($pathToFile));
            break;
        case 'yaml':
        case 'yml':
            $parsedFile = Yaml::parse(file_get_contents($pathToFile), Yaml::PARSE_OBJECT_FOR_MAP);
            break;
    }
    return $parsedFile;
}
