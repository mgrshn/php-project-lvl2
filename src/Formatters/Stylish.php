<?php

namespace Differ\Formatters\Stylish;

use Functional;

function stylish(array $treeOfFiles): array
{
    $res = array_reduce($treeOfFiles, function ($acc, $node) {
        switch ($node) {
            case $node['status'] === 'not changed':
                $acc["{$node['name']}"] = $node["value"];
                return $acc;
                break;
            case $node['status'] === 'added':
                $acc["+ {$node['name']}"] = $node["value"];
                return $acc;
                break;
            case $node['status'] === 'removed':
                $acc["- {$node['name']}"] = $node["value"];
                return $acc;
                break;
            case $node['status'] === 'changed':
                $acc["- {$node['name']}"] = $node["oldValue"];
                $acc["+ {$node['name']}"] = $node["newValue"];
                return $acc;
                break;
            case $node['status'] === 'nested':
                $acc["{$node['name']}"] = stylish($node['child']);
                return $acc;
                break;
        }
        //return $acc;
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
