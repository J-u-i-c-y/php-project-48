<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $filepath): array
{
    $content = file_get_contents($filepath);
    $ext = pathinfo($filepath, PATHINFO_EXTENSION);

    return match (strtolower($ext)) {
        'json' => parseJson($content),
        'yaml', 'yml' => parseYaml($content),
        default => throw new \Exception("Unsupported file format: $ext"),
    };
}

function parseJson(string $content): array
{
    $decoded = json_decode($content, true);
    if (!is_array($decoded)) {
        throw new \Exception("Invalid JSON: expected flat key-value object");
    }
    return $decoded;
}

function parseYaml(string $content): array
{
    $parsed = Yaml::parse($content, Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);

    if ($parsed instanceof \stdClass) {
        $parsed = (array) $parsed;
    }

    if (!is_array($parsed)) {
        throw new \Exception('Invalid YAML: expected flat key-value object');
    }

    return $parsed;
}

