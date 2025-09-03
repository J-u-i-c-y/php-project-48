<?php

namespace Differ\Differ;

use Exception;

use function Functional\sort;
use function Functional\reduce_left;
use function Differ\Parser\getContentFile;
use function Differ\Parser\parse;
use function Differ\Parser\arrayToObject;
use function Differ\Formatter\format;

function genDiff(string $filepath1, string $filepath2, string $format = 'stylish'): string
{
    $content1 = getContentFile($filepath1);
    $content2 = getContentFile($filepath2);

    $ext1 = pathinfo($filepath1, PATHINFO_EXTENSION);
    $ext2 = pathinfo($filepath2, PATHINFO_EXTENSION);

    $data1 = parse($content1, $ext1);
    $data2 = parse($content2, $ext2);

    $file1Data = arrayToObject($data1);
    $file2Data = arrayToObject($data2);

    $diff = buildDiff($file1Data, $file2Data);
    return format($diff, $format);
}

function buildDiff(object $file1Data, object $file2Data): array
{
    $keys1 = array_keys(get_object_vars($file1Data));
    $keys2 = array_keys(get_object_vars($file2Data));
    $allKeys = array_unique(array_merge($keys1, $keys2));

    $sortedKeys = sort($allKeys, fn($a, $b) => strcmp($a, $b));

    return reduce_left($sortedKeys, function ($key, $index, $collection, $reduction) use ($file1Data, $file2Data) {
        $hasKey1 = property_exists($file1Data, $key);
        $hasKey2 = property_exists($file2Data, $key);
        $value1 = $file1Data->$key ?? null;
        $value2 = $file2Data->$key ?? null;

        $node = match (true) {
            $hasKey1 && !$hasKey2 => ['type' => 'removed', 'key' => $key, 'value' => $value1],
            !$hasKey1 && $hasKey2 => ['type' => 'added', 'key' => $key, 'value' => $value2],
            is_object($value1) && is_object($value2) => [
                'type' => 'nested',
                'key' => $key,
                'children' => buildDiff($value1, $value2)
            ],
            $value1 === $value2 => ['type' => 'unchanged', 'key' => $key, 'value' => $value1],
            default => ['type' => 'changed', 'key' => $key, 'oldValue' => $value1, 'newValue' => $value2],
        };

        return array_merge($reduction, [$node]);
    }, []);
}
