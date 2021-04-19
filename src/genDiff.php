<?php

namespace App\GenDiff;

use function App\Parsers\parse;
use function App\Ast\Builder\generateAst;
use function App\Formatters\Plain\runPlainRender;
use function App\Formatters\Pretty\runPrettyRender;
use function App\Formatters\Json\runJsonRender;

function genDiff($pathToFile1, $pathToFile2, $format)
{
    checkPath($pathToFile1);
    checkPath($pathToFile2);
    $data1 = getData($pathToFile1);
    $data2 = getData($pathToFile2);
    $ast = generateAst($data1, $data2);
    $renderedAst = chooseRender($ast, $format);
    return $renderedAst;
}

function getData($pathToFile)
{
    $data = file_get_contents($pathToFile);
    if (!$data) {
        throw new \Exception("Can't get a content from {$pathToFile}");
    }
    $dataType = pathinfo($pathToFile, PATHINFO_EXTENSION);
    return parse($data, $dataType);
}

function chooseRender($ast, $format)
{
    $renders = [
        'pretty' => function ($ast) {
            return runPrettyRender($ast);
        },
        'plain' => function ($ast) {
            return runPlainRender($ast);
        },
        'json' => function ($ast) {
            return runJsonRender($ast);
        }
    ];
    return $renders[$format]($ast);
}

function checkPath($pathToFile)
{
    if (!is_file($pathToFile)) {
        throw new \Exception("{$pathToFile} is not a file");
    }
    if (!is_readable($pathToFile)) {
        throw new \Exception("{$pathToFile} is not readable");
    }
}
