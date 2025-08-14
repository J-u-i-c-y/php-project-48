<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\genDiff;

class JsonFormatterTest extends TestCase
{
    public function testJsonFormat(): void
    {
        $expectedFile = __DIR__ . '/fixtures/expected_json.txt';
        $path1 = __DIR__ . '/fixtures/file1.json';
        $path2 = __DIR__ . '/fixtures/file2.json';

        $result = genDiff($path1, $path2, 'json');

        $this->assertJsonStringEqualsJsonString(
            file_get_contents($expectedFile),
            $result
        );
    }
}
