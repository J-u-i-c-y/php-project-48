<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\gendiff;

class DiffYamlTest extends TestCase
{
    public function testFlatYamlDiff()
    {
        $file1 = __DIR__ . '/fixtures/file1.yml';
        $file2 = __DIR__ . '/fixtures/file2.yml';

        $expected = <<<TEXT
{
    follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
TEXT;

        $this->assertSame($expected, gendiff($file1, $file2));
    }
}
