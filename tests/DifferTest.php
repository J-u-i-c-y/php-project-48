<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiffJsonYamlNested()
    {
        $jsonPath1 = $this->getFixtureFullPath('file1.json');
        $jsonPath2 = $this->getFixtureFullPath('file2.json');
        $yamlPath1 = $this->getFixtureFullPath('file1.yml');
        $yamlPath2 = $this->getFixtureFullPath('file2.yml');

        $expectedStylish = $this->getContentFixture('stylish.txt');
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2));

        $expectedStylish = $this->getContentFixture('plain.txt');
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2, 'plain'));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2, 'plain'));

        $expectedStylish = $this->getContentFixture('json.txt');
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2, 'json'));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2, 'json'));
    }

    public function getFixtureFullPath($fixtureName): false|string
    {
        $parts = [__DIR__, 'Fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function getContentFixture($fixtureName): false|string
    {
        return file_get_contents($this->getFixtureFullPath($fixtureName));
    }
}