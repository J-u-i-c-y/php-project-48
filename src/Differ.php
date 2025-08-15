<?php

namespace Gendiff;

use function Gendiff\Parsers\parse;
use function Gendiff\Formatters\format;

function genDiff(string $path1, string $path2, string $formatName = 'stylish'): string
{
    $data1 = parse($path1);
    $data2 = parse($path2);

    $diffTree = buildDiff($data1, $data2);

    return format($diffTree, $formatName);
}

function buildDiff(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($keys);

    return array_map(function ($key) use ($data1, $data2) {
        $val1 = $data1[$key] ?? null;
        $val2 = $data2[$key] ?? null;

        if (!array_key_exists($key, $data2)) {
            return ['key' => $key, 'type' => 'removed', 'value' => $val1];
        }
        if (!array_key_exists($key, $data1)) {
            return ['key' => $key, 'type' => 'added', 'value' => $val2];
        }
        if (is_array($val1) && is_array($val2)) {
            return ['key' => $key, 'type' => 'nested', 'children' => buildDiff($val1, $val2)];
        }
        if ($val1 !== $val2) {
            return ['key' => $key, 'type' => 'changed', 'oldValue' => $val1, 'newValue' => $val2];
        }
        return ['key' => $key, 'type' => 'unchanged', 'value' => $val1];
    }, $keys);
}
