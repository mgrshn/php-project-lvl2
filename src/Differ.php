<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Builder\build;
use function Differ\Formatter\format;
use function Differ\Formatter\toString;

function genDiff($pathToFile1, $pathToFile2)
{
    $firstFile = parse($pathToFile1);
    $secondFile = parse($pathToFile2);

    $files = build($firstFile, $secondFile);
    $formattedFilesTree = format($files);
    $result = toString($formattedFilesTree);
    return $result;
}
