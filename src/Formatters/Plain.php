<?php

namespace Gendiff\Formatters\Plain;

function formatPlain(array $tree): string
{
    $lines = iter($tree);
    return implode("\n", $lines);
}

function iter(array $tree, string $path = ''): array
{
    $lines = [];

    foreach ($tree as $node) {
        $property = $path === '' ? $node['key'] : "{$path}.{$node['key']}";

        switch ($node['type']) {
            case 'nested':
                $lines = array_merge($lines, iter($node['children'], $property));
                break;

            case 'added':
                $lines[] = "Property '{$property}' was added with value: " . stringifyPlain($node['value']);
                break;

            case 'removed':
                $lines[] = "Property '{$property}' was removed";
                break;

            case 'changed':
                $lines[] = "Property '{$property}' was updated. From "
                    . stringifyPlain($node['oldValue'])
                    . " to "
                    . stringifyPlain($node['newValue']);
                break;

            case 'unchanged':
                break;
        }
    }

    return $lines;
}

function stringifyPlain($value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value === null) {
        return 'null';
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    return (string)$value;
}
