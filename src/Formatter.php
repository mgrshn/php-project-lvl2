<?php

namespace Differ\formatter;

function stylish(array $treeOfFiles): array
{
    $res = [];
    foreach ($treeOfFiles as $file) {
        if ($file['status'] === 'nested') {
            $res["{$file['name']}"] = stylish($file['child']);
        } elseif ($file['status'] === 'not changed') {
            $res["{$file['name']}"] = $file['value'];
        } elseif ($file['status'] === 'added') {
            $res["+ {$file['name']}"] = $file['value'];
        } elseif ($file['status'] === 'removed') {
            $res["- {$file['name']}"] = $file['value'];
        } else {
            $res["- {$file['name']}"] = $file['oldValue'];
            $res["+ {$file['name']}"] = $file['newValue'];
        }
    }
    return $res;
}

function toString(array $formattedArray): string
{
    $result = str_replace('"', '', json_encode($formattedArray, JSON_PRETTY_PRINT));
    $result = str_replace(",", "", $result);
    $result = str_replace("  + ", "+ ", $result);
    $result = str_replace("  - ", "- ", $result);
    return $result;
}
