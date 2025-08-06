<?php

namespace Hexlet\Code;

use Funct\Collection;

function genDiff(string $path1, string $path2): string
{
    $data1 = json_decode(file_get_contents($path1), true);
    $data2 = json_decode(file_get_contents($path2), true);

    $allKeys = array_keys(array_merge($data1, $data2));
    $sortedKeys = Collection\sortBy($allKeys, fn($key) => $key);

    $lines = array_map(function ($key) use ($data1, $data2) {
        $has1 = array_key_exists($key, $data1);
        $has2 = array_key_exists($key, $data2);
        $val1 = $data1[$key] ?? null;
        $val2 = $data2[$key] ?? null;

        if ($has1 && !$has2) {
            return "  - {$key}: " . toString($val1);
        } elseif (!$has1 && $has2) {
            return "  + {$key}: " . toString($val2);
        } elseif ($val1 !== $val2) {
            return "  - {$key}: " . toString($val1) . "\n  + {$key}: " . toString($val2);
        } else {
            return "    {$key}: " . toString($val1);
        }
    }, $sortedKeys);

    return "{\n" . implode("\n", $lines) . "\n}";
}

function toString($value): string
{
    return is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
}
