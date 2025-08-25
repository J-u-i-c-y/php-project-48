<?php

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    #[DataProvider('filesProvider')]
    public function testGenDiff(string $file1, string $file2, string $format, string $expectedFixture): void
    {
        $expected = $this->getContentFixture($expectedFixture);
        $this->assertEquals($expected, genDiff($file1, $file2, $format));
    }

    public static function filesProvider(): array
    {
        $fixturesDir = __DIR__ . '/Fixtures';
        
        $json1 = realpath($fixturesDir . '/file1.json');
        $json2 = realpath($fixturesDir . '/file2.json');
        $yaml1 = realpath($fixturesDir . '/file1.yml');
        $yaml2 = realpath($fixturesDir . '/file2.yml');

        return [
            [$json1, $json2, 'stylish', 'stylish.txt'],
            [$yaml1, $yaml2, 'stylish', 'stylish.txt'],
            [$json1, $json2, 'plain', 'plain.txt'],
            [$yaml1, $yaml2, 'plain', 'plain.txt'],
            [$json1, $json2, 'json', 'json.txt'],
            [$yaml1, $yaml2, 'json', 'json.txt'],
        ];
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

    



// class UserTest extends TestCase
// {
//     #[DataProvider('userDataProvider')]
//     public function testGetName(string $name, array $children): void
//     {
//         $user = new User($name, $children);

//         $this->assertEquals($name, $user->getName());
//         $this->assertEquals(collect($children), $user->getChildren());
//     }

//     public static function userDataProvider(): array
//     {
//         return [
//             'one·child' => ['john', [new User('Mark')]],
//             'no children' => ['anna', []],
//             'two children' => ['alice', [new User('Tom'), new User('Eva')]],
//         ];
//     }
// }