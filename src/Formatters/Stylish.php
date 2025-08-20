<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $diff, int $depth = 0): string
{
    $baseIndent = buildIndent($depth);
    $nodeIndent = buildIndent($depth, 4);
    $signIndent = buildIndent($depth, 2);

    $resultBody = array_map(function ($node) use ($depth, $nodeIndent, $signIndent) {
        $key = $node['key'];
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                $childrenFormatted = formatStylish($node['children'], $depth + 1);
                return ["{$nodeIndent}{$key}: {$childrenFormatted}"];

            case 'unchanged':
                $value = stringifyValue($node['value'], $depth + 1);
                return ["{$nodeIndent}{$key}: {$value}"];

            case 'added':
                $value = stringifyValue($node['value'], $depth + 1);
                return ["{$signIndent}+ {$key}: {$value}"];

            case 'removed':
                $value = stringifyValue($node['value'], $depth + 1);
                return ["{$signIndent}- {$key}: {$value}"];

            case 'changed':
                $oldValue = stringifyValue($node['oldValue'], $depth + 1);
                $newValue = stringifyValue($node['newValue'], $depth + 1);
                return [
                    "{$signIndent}- {$key}: {$oldValue}",
                    "{$signIndent}+ {$key}: {$newValue}"
                ];
        }
        return [];
    }, $diff);

    $flattenedBody = array_merge([], ...$resultBody);
    $result = array_merge(["{"], $flattenedBody, ["{$baseIndent}}"]);
    return implode("\n", $result);
}

function stringifyValue(mixed $value, int $depth = 0): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_object($value) || is_array($value)) {
        $baseIndent = buildIndent($depth);
        $contentIndent = buildIndent($depth, 4);

        $items = is_object($value) ? get_object_vars($value) : $value;
        $lines = array_map(function ($key) use ($items, $depth, $contentIndent) {
            $itemValue = stringifyValue($items[$key], $depth + 1);
            return "{$contentIndent}{$key}: {$itemValue}";
        }, array_keys($items));

        $result = array_merge(["{"], $lines, ["{$baseIndent}}"]);
        return implode("\n", $result);
    }
    return (string)$value;
}

function buildIndent(int $depth, int $addSpaces = 0): string
{
    return str_repeat(' ', $depth * 4 + $addSpaces);
}
