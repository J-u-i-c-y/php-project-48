<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $format): object
{
    return match ($format) {
        'json' => json_decode($content),
        'yaml', 'yml' => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP),
        default => throw new InvalidArgumentException("Unsupported data format: '$format'. Expected 'json' or 'yaml'."),
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
