<?php

namespace Differ\Differ;

function genDiff($pathToFile1, $pathToFile2): string
{
    if (!file_exists($pathToFile1)) {
        $pathToFile1 = __DIR__ . "/../tests/fixtures/{$pathToFile1}";
    }
    if (!file_exists($pathToFile2)) {
        $pathToFile2 = __DIR__ . "/../tests/fixtures/{$pathToFile2}";
    }
    $firstFileAsArray = json_decode(file_get_contents($pathToFile1), true);
    $secondFileAsArray = json_decode(file_get_contents($pathToFile2), true);
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
