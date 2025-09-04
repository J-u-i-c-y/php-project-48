<?php

namespace Differ\Parser;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $extension): object
{
    return match ($extension) {
        'json' => json_decode($content, false, 512, JSON_THROW_ON_ERROR),
        'yml', 'yaml' => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP),
        'ini' => json_decode(json_encode(parse_ini_string($content, true)), false),
        default => throw new Exception("Unsupported file format: {$extension}"),
    };
}
