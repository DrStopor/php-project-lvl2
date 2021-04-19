<?php

namespace App\Formatters\Pretty;

function runPrettyRender($ast): string
{
    $result = renderPretty($ast);
    return "{\n$result\n}";
}

function renderPretty($ast, $level = 0): string
{
    $indent = str_repeat('    ', $level);
    $changes = array_reduce($ast, function ($acc, $node) use ($indent, $level) {
        switch ($node['type']) {
            case 'not changed':
                $value = getValue($node['value'], $level);
                $acc[] = "{$indent}    {$node['key']}: {$value}";
                break;
            case 'added':
                $value = getValue($node['value'], $level);
                $acc[] = "{$indent}  + {$node['key']}: {$value}";
                break;
            case 'deleted':
                $value = getValue($node['value'], $level);
                $acc[] = "{$indent}  - {$node['key']}: {$value}";
                break;
            case 'changed':
                $oldValue = getValue($node['oldValue'], $level);
                $newValue = getValue($node['newValue'], $level);
                $acc[] = "{$indent}  - {$node['key']}: {$oldValue}";
                $acc[] = "{$indent}  + {$node['key']}: {$newValue}";
                break;
            case 'parent':
                $children = renderPretty($node['children'], $level + 1);
                $acc[] = "{$indent}    {$node['key']}: {\n{$children}\n    {$indent}}";
                break;
        }
        return $acc;
    });
    $result = implode(PHP_EOL, $changes);
    return $result;
}

function getValue($data, $level)
{
    if (is_array($data)) {
        return arrayToValue($data, $level);
    } else {
        return $data;
    }
}

function arrayToValue($data, $level)
{
    $indent = str_repeat('    ', $level + 1);
    $keys = array_keys($data);
    $values = array_reduce($keys, function ($acc, $value) use ($indent, $data) {
        $acc[] = "{$indent}  {$value}: {$data[$value]}";
        return $acc;
    });
    $result = implode(PHP_EOL, $values);
    return "{\n$result\n{$indent}}";
}
