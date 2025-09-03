<?php

namespace Differ\Parser;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $extension): mixed
{
    return match (strtolower($extension)) {
        'json' => json_decode($content),
        'yml', 'yaml' => Yaml::parse($content),
        'ini' => parse_ini_string($content, true),
        default => throw new \Exception("Unsupported file format: {$extension}"),
    };
}

function getContentFile(string $filepath): string
{
    $content = file_get_contents($filepath);
    if ($content === false) {
        throw new Exception('Unable to read file: ' . $filepath);
    }
    return $content;
}

function arrayToObject(mixed $data): mixed
{
    if (is_array($data)) {
        return (object) array_map(fn($item) => arrayToObject($item), $data);
    }
    return $data;
}
