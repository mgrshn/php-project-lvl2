<?php

namespace Differ\Differ;

use function Differ\Parser\parse;

function genDiff($pathToFile1, $pathToFile2): string
{
    $firstFileAsArray = parse($pathToFile1);
    $secondFileAsArray = parse($pathToFile2);
    $mergedArray = array_merge_recursive($firstFileAsArray, $secondFileAsArray);
    ksort($mergedArray);
    $resultArr = [];
    foreach ($mergedArray as $key => $value) {
        if (is_array($value) && $value[0] === $value[1]) {
            $resultArr["  {$key}"] = $value[0];
        } elseif (is_array($value) && $value[0] !== $value[1]) {
            $resultArr["- {$key}"] = $value[0];
            $resultArr["+ {$key}"] = $value[1];
        } elseif (!array_key_exists($key, $secondFileAsArray)) {
            $resultArr["- {$key}"] = $value;
        } else {
            $resultArr["+ {$key}"] = $value;
        }
    }
    $result = str_replace('"', '', json_encode($resultArr));
    $result = str_replace('{', "{\n  ", $result);
    $result = str_replace('}', "\n}", $result);
    $result = str_replace(",", "\n  ", $result);
    $result = str_replace(":", ": ", $result);
    return $result;
}
