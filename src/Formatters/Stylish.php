<?php

namespace Differ\Formatters\Stylish;

use Functional;

function stylish(array $treeOfFiles): array
{
    $res = array_reduce($treeOfFiles, function ($accc, $node) {
        switch ($node) {
            case $node['status'] === 'not changed':
                $accc["{$node['name']}"] = $node["value"];//Should not use of mutating operators
                $acc = $accc;
                break;
            case $node['status'] === 'added':
                $accc["+ {$node['name']}"] = $node["value"];//Should not use of mutating operators
                $acc = $accc;
                break;
            case $node['status'] === 'removed':
                $accc["- {$node['name']}"] = $node["value"];//Should not use of mutating operators
                $acc = $accc;
                break;
            case $node['status'] === 'changed':
                $accc["- {$node['name']}"] = $node["oldValue"];//Should not use of mutating operators
                $accc["+ {$node['name']}"] = $node["newValue"];//Should not use of mutating operators
                $acc = $accc;
                break;
            case $node['status'] === 'nested':
                $accc["{$node['name']}"] = stylish($node['child']);//Should not use of mutating operators
                $acc = $accc;
                break;
        }
        //return $newAcc;
        return $acc;
    }, []);
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
