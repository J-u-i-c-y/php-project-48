<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $tree, int $depth = 1): string
{
    $indentSize = 4;
    $currentIndent = str_repeat(' ', $depth * $indentSize - 2);
    $bracketIndent = str_repeat(' ', ($depth - 1) * $indentSize);

    $lines = array_map(function ($node) use ($depth, $currentIndent) {
        switch ($node['type']) {
            case 'nested':
                $children = formatStylish($node['children'], $depth + 1);
                return "{$currentIndent}  {$node['key']}: {$children}";
            case 'added':
                return "{$currentIndent}+ {$node['key']}: " . stringify($node['value'], $depth + 1);
            case 'removed':
                return "{$currentIndent}- {$node['key']}: " . stringify($node['value'], $depth + 1);
            case 'unchanged':
                return "{$currentIndent}  {$node['key']}: " . stringify($node['value'], $depth + 1);
            case 'changed':
                $old = "{$currentIndent}- {$node['key']}: " . stringify($node['oldValue'], $depth + 1);
                $new = "{$currentIndent}+ {$node['key']}: " . stringify($node['newValue'], $depth + 1);
                return "{$old}\n{$new}";
            default:
                throw new \Exception("Unknown node type: {$node['type']}");
        }
    }, $tree);

    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}

function stringify($value, int $depth): string
{
    if (!is_array($value)) {
        if ($value === null) {
            return 'null';
        }
        return is_bool($value) ? ($value ? 'true' : 'false') : (string)$value;
    }

    $indentSize = 4;
    $currentIndent = str_repeat(' ', $depth * $indentSize);
    $bracketIndent = str_repeat(' ', ($depth - 1) * $indentSize);

    $lines = array_map(
        function ($k, $v) use ($depth, $currentIndent) {
            return "{$currentIndent}{$k}: " . stringify($v, $depth + 1);
        },
        array_keys($value),
        $value
    );

    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}
