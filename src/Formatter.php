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

function plain(array $mergedTree, $accName = ''/*, $res = []*/)
{
    //var_dump($mergedTree);
    //print_r($mergedTree);
    /*foreach ($mergedTree as $elem) {
        $name = $elem['name'];
        print_r($name);
        if ($elem['status'] === 'nested') {
            $accName .= $name . '.';
            return plain($elem['child'], $accName, $res);
        } elseif ($elem['status'] !== 'nested') {
            $res[] = 'Property ' . $accName . $elem['name'] . ' was changed';
        }
    }
    print_r($res);
    return $res;*/
    $lines = array_map(function ($elem) use ($accName) {
        $name = $elem['name'];
        if ($elem['status'] === 'nested') {
            $accName .= $name . '.';
            return plain($elem['child'], $accName);
        } elseif ($elem['status'] === 'added') {
            $value = is_object($elem['value']) ? '[complex value]' : var_export($elem['value'], true);
            return "Property '{$accName}{$elem['name']}' was added with value: {$value}";
        } elseif ($elem['status'] === 'removed') {
            return "Property '{$accName}{$elem['name']}' was removed";
        } elseif ($elem['status'] === 'changed') {
            $oldVal = is_object($elem['oldValue']) ? '[complex value]' : var_export($elem['oldValue'], true);
            $oldVal = $oldVal === 'NULL' ? 'null' : $oldVal;
            $newVal = is_object($elem['newValue']) ? '[complex value]' : var_export($elem['newValue'], true);
            $newVal = $newVal === 'NULL' ? 'null' : $newVal;
            return "Property '{$accName}{$elem['name']}' was updated. From {$oldVal} to {$newVal}";
        } elseif ($elem['status'] === 'removed') {
            return "Property '{$accName}{$elem['name']}' was removed";
        };
    }, $mergedTree);

    $lines = array_filter($lines, fn($line) => $line);//claer clean lines from $lines array with value not changed.
    return implode("\n", $lines);
}
