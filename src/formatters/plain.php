<?php

namespace App\Formatters\Plain;

function runPlainRender($ast): string
{
    $result = renderPlain($ast);
    return $result;
}

function renderPlain($ast, $origin = null): string
{
    $changes = array_reduce($ast, function ($acc, $node) use ($origin) {
        switch ($node['type']) {
            case 'added':
                $name = getPropertyName($node, $origin);
                $value = getValue($node['value']);
                $acc[] = "Property '{$name}' was added with value: '{$value}'";
                break;
            case 'deleted':
                $name = getPropertyName($node, $origin);
                $acc[] = "Property '{$name}' was removed";
                break;
            case 'changed':
                $name = getPropertyName($node, $origin);
                $oldValue = getValue($node['oldValue']);
                $newValue = getValue($node['newValue']);
                $acc[] = "Property '{$name}' was changed. From '{$oldValue}' to '{$newValue}'";
                break;
            case 'parent':
                $acc[] = renderPlain($node['children'], $node['key']);
                break;
        }
        return $acc;
    });
    $result = implode(PHP_EOL, $changes);
    return $result;
}

function getPropertyName($node, $origin)
{
    if ($origin === null) {
        return "{$node['key']}";
    }
    return "{$origin}.{$node['key']}";
}

function getValue($node)
{
    if (is_array($node)) {
        return 'complex value';
    }
    return $node;
}
