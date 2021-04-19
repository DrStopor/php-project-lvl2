<?php

namespace App\Formatters\Json;

function runJsonRender($ast)
{
    $result = renderJson($ast);
    return $result;
}

function renderJson($ast)
{
    return json_encode($ast);
}
