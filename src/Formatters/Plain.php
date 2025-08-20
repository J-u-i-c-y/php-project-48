<?php

namespace Differ\Formatters\Plain;

use function Functional\map;

function stringifyPlainValue(mixed $value): string
{
    if (is_object($value) || is_array($value)) {
        return '[complex value]';
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    return (string)$value;
}

function formatPlain(array $diff, string $parentKey = ''): string
{
    $processNode = function ($node) use ($parentKey) {
        $key = $node['key'];
        $fullKey = $parentKey !== '' ? "{$parentKey}.{$key}" : $key;
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                return formatPlain($node['children'], $fullKey);
            case 'added':
                $value = stringifyPlainValue($node['value']);
                return "Property '{$fullKey}' was added with value: {$value}";
            case 'removed':
                return "Property '{$fullKey}' was removed";
            case 'changed':
                $oldValue = stringifyPlainValue($node['oldValue']);
                $newValue = stringifyPlainValue($node['newValue']);
                return "Property '{$fullKey}' was updated. From {$oldValue} to {$newValue}";
            case 'unchanged':
                return null;
            default:
                throw new \Exception("Unknown node type: {$type}");
        }
    };

    $lines = map($diff, $processNode);
    $filteredLines = array_filter($lines, fn($line) => $line !== null);

    return implode("\n", $filteredLines);
}
