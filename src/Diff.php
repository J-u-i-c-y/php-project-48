<?php

namespace Gendiff;

use function Gendiff\Parsers\parse;

function gendiff(string $path1, string $path2): string
{
    $data1 = parse($path1);
    $data2 = parse($path2);

    $keys = array_keys(array_merge($data1, $data2));
    sort($keys);

    $lines = ['{'];

    foreach ($keys as $key) {
        $val1 = $data1[$key] ?? null;
        $val2 = $data2[$key] ?? null;

        if (!array_key_exists($key, $data2)) {
            $lines[] = "  - {$key}: " . toString($val1);
        } elseif (!array_key_exists($key, $data1)) {
            $lines[] = "  + {$key}: " . toString($val2);
        } elseif ($val1 !== $val2) {
            $lines[] = "  - {$key}: " . toString($val1);
            $lines[] = "  + {$key}: " . toString($val2);
        } else {
            $lines[] = "    {$key}: " . toString($val1);
        }
    }

    $lines[] = '}';
    return implode("\n", $lines);
}

function toString(mixed $value): string
{
    return is_bool($value) ? ($value ? 'true' : 'false') : (string)$value;
}
