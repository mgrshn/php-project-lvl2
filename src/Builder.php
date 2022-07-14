<?php

namespace Differ\Builder;

function build(object $firstFile, object $secondFile): array
{
    $keys = array_unique(array_merge(
        array_keys(get_object_vars($firstFile)),
        array_keys(get_object_vars($secondFile))
    ));
    sort($keys);

    $files = [];
    foreach ($keys as $key) {
        if (
            property_exists($firstFile, $key) &&
            property_exists($secondFile, $key) &&
            $secondFile->$key == $firstFile->$key
        ) {
            $files[$key] = [
                'name' => $key,
                'status' => 'not changed',
                'value' => $secondFile->$key, true
            ];
        } elseif (!property_exists($firstFile, $key)) {
            $files[$key] = [
                'name' => $key,
                'status' => 'added',
                'value' => $secondFile->$key, true
            ];
        } elseif (!property_exists($secondFile, $key)) {
            $files[$key] = [
                'name' => $key,
                'status' => 'removed',
                'value' => $firstFile->$key, true
            ];
        } elseif (
            property_exists($firstFile, $key) &&
            property_exists($secondFile, $key) &&
            $secondFile->$key != $firstFile->$key &&
            !(is_object($firstFile->$key)) || !(is_object($secondFile->$key))
        ) {
            $files[$key] = [
                'name' => $key,
                'status' => 'changed',
                'newValue' => $secondFile->$key, true,
                'oldValue' => $firstFile->$key, true
            ];
        } else {
            if (is_object($firstFile->$key) && is_object($secondFile->$key)) {
                $files[$key] = [
                    'name' => $key,
                    'status' => 'nested',
                    'child' => build($firstFile->$key, $secondFile->$key)
                ];
            }
        }
    }
    return $files;
}
