<?php

namespace Gendiff\Formatters\Stylish;

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
                return "{$currentIndent}+ {$node['key']}: " . stringify($node['value'], $depth);
            case 'removed':
                return "{$currentIndent}- {$node['key']}: " . stringify($node['value'], $depth);
            case 'unchanged':
                return "{$currentIndent}  {$node['key']}: " . stringify($node['value'], $depth);
            case 'changed':
                $old = "{$currentIndent}- {$node['key']}: " . stringify($node['oldValue'], $depth);
                $new = "{$currentIndent}+ {$node['key']}: " . stringify($node['newValue'], $depth);
                return "{$old}\n{$new}";
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
        fn($k, $v) => "{$currentIndent}{$k}: " . stringify($v, $depth + 1),
        array_keys($value),
        $value
    );
    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}
