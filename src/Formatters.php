<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Stylish\toString;

function formatTree(string $format, array $tree)
{
    if ($format === 'stylish') {
        return toString(stylish($tree));
    } elseif ($format === 'plain') {
        return plain($tree);
    }
}
