<?php

namespace Gendiff\Formatters;

use function Gendiff\Formatters\Stylish\formatStylish;
use function Gendiff\Formatters\Plain\formatPlain;
use function Gendiff\Formatters\Json\formatJson;

function format(array $diff, string $formatName): string
{
    return match ($formatName) {
        'stylish' => formatStylish($diff),
        'plain'   => formatPlain($diff),
        'json'    => formatJson($diff),
        default   => throw new \Exception("Unknown format: {$formatName}")
    };
}
