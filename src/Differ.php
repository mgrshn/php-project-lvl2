<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Builder\build;
use function Differ\Formatter\stylish;
use function Differ\Formatter\toString;

function genDiff(string $pathToFile1, string $pathToFile2, string $format)
{
    $firstFile = parse($pathToFile1);
    $secondFile = parse($pathToFile2);
    $files = build($firstFile, $secondFile);

    if ($format === 'stylish') {
        $formattedFilesTree = stylish($files);
        $result = toString($formattedFilesTree);
    }

    return $result;
}
