<?php

namespace Differ\Formatters\Stylish;

use Functional;

function stylish(array $treeOfFiles): array
{
    $res = array_reduce($treeOfFiles, function ($acc, $node) {
        switch ($node) {
            case $node['status'] === 'not changed':
                $acc["{$node['name']}"] = $node["value"];//Should not use of mutating operators
                break;
            case $node['status'] === 'added':
                $acc["+ {$node['name']}"] = $node["value"];//Should not use of mutating operators
                break;
            case $node['status'] === 'removed':
                $acc["- {$node['name']}"] = $node["value"];//Should not use of mutating operators
                break;
            case $node['status'] === 'changed':
                $acc["- {$node['name']}"] = $node["oldValue"];//Should not use of mutating operators
                $acc["+ {$node['name']}"] = $node["newValue"];//Should not use of mutating operators
                break;
            case $node['status'] === 'nested':
                $acc["{$node['name']}"] = stylish($node['child']);//Should not use of mutating operators
                break;
        }
        //return $newAcc;
        return $acc;
    });
    //print_r($res);
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
