<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use function App\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testFlatPretty()
    {
        $path1 = 'tests/fixtures/flatBefore.json';
        $path2 = 'tests/fixtures/flatAfter.json';
        $expected = file_get_contents("tests/fixtures/expected/flatPretty");
        $this->assertEquals($expected, genDiff($path1, $path2, 'pretty'));
        $this->assertEquals($expected, genDiff($path1, 'tests/fixtures/flatAfter.yaml', 'pretty')); // json and yaml
    }
    public function testFlatPlain()
    {
        $path1 = 'tests/fixtures/flatBefore.json';
        $path2 = 'tests/fixtures/flatAfter.json';
        $expected = file_get_contents("tests/fixtures/expected/flatPlain");
        $this->assertEquals($expected, genDiff($path1, $path2, 'plain'));
        $this->assertEquals($expected, genDiff($path1, 'tests/fixtures/flatAfter.yaml', 'plain')); // json and yaml
    }
    public function testFlatJson()
    {
        $path1 = 'tests/fixtures/flatBefore.json';
        $path2 = 'tests/fixtures/flatAfter.json';
        $expected = file_get_contents("tests/fixtures/expected/flatJson");
        $this->assertEquals($expected, genDiff($path1, $path2, 'json'));
        $this->assertEquals($expected, genDiff($path1, 'tests/fixtures/flatAfter.yaml', 'json')); // json and yaml
    }

    public function testNestedPretty()
    {
        $path1 = 'tests/fixtures/nestedBefore.json';
        $path2 = 'tests/fixtures/nestedAfter.json';
        $expected = file_get_contents("tests/fixtures/expected/nestedPretty");
        $this->assertEquals($expected, genDiff($path1, $path2, 'pretty'));
    }
    public function testNestedPlain()
    {
        $path1 = 'tests/fixtures/nestedBefore.json';
        $path2 = 'tests/fixtures/nestedAfter.json';
        $expected = file_get_contents("tests/fixtures/expected/nestedPlain");
        $this->assertEquals($expected, genDiff($path1, $path2, 'plain'));
    }
    public function testNestedJson()
    {
        $path1 = 'tests/fixtures/nestedBefore.json';
        $path2 = 'tests/fixtures/nestedAfter.json';
        $expected = file_get_contents("tests/fixtures/expected/nestedJson");
        $this->assertEquals($expected, genDiff($path1, $path2, 'json'));
    }
}
