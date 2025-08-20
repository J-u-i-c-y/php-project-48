<?php

namespace Differ\Formatters\Json;

function formatJson(array $diff): string
{
    $result = json_encode($diff, JSON_UNESCAPED_UNICODE);

    if ($result === false) {
        throw new \RuntimeException('Failed to encode JSON');
    }

    return $result;
}
