<?php

namespace Differ\Parser;

use Exception;
use InvalidArgumentException;
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
