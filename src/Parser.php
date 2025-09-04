<?php

namespace Differ\Parser;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $extension): object
{
    return match ($extension) {
        'json' => json_decode($content),
        'yml', 'yaml' => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP),
        'ini' => parse_ini_string($content, true),
        default => throw new Exception("Unsupported file format: {$extension}"),
    };
}
