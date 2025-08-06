<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class CompareJsonTest extends TestCase
{
    public function testSimpleEquality()
    {
        $json1 = json_decode('{"a": 1, "b": 2}', true);
        $json2 = json_decode('{"b": 2, "a": 1}', true);

        $this->assertEqualsCanonicalizing($json1, $json2);
    }
}
