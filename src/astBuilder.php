<?php

namespace App\Ast\Builder;

use function Funct\Collection\union;

function generateAst($data1, $data2)
{
    $keys = union(array_keys($data1), array_keys($data2));
    $ast = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
        $acc[] = getData($key, $data1, $data2);
        return $acc;
    });
    return $ast;
}

function getData($key, $data1, $data2)
{
    if (!array_key_exists($key, $data1)) {
        return ['type' => 'added', 'key' => $key, 'value' => $data2[$key]];
    }
    if (!array_key_exists($key, $data2)) {
        return ['type' => 'deleted', 'key' => $key, 'value' => $data1[$key]];
    }
    if (is_array($data1[$key]) && is_array($data2[$key])) {
        return ['type' => 'parent', 'key' => $key, 'children' => generateAst($data1[$key], $data2[$key])];
    }
    if ($data1[$key] === $data2[$key]) {
        return ['type' => 'not changed', 'key' => $key, 'value' => $data1[$key]];
    }
    if ($data1[$key] !== $data2[$key]) {
        return ['type' => 'changed', 'key' => $key, 'oldValue' => $data1[$key], 'newValue' => $data2[$key]];
    }
}
