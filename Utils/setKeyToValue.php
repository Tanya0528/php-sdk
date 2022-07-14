<?php

namespace Utils;

function setKeyToValue(string $key, $value, array &$array)
{
    $keys = explode('.', $key);

    $k = array_shift($keys);

    addKeyToArray($k, $keys, $value, $array);
}

function addKeyToArray(string $k, array $keys, $value, array &$array)
{
    if (!count($keys)) {
        $array[$k] = $value;
        return;
    }

    if (!array_key_exists($k, $array)) {
        $array[$k] = [];
    }

    $nextKey = array_shift($keys);

    addKeyToArray($nextKey, $keys, $value, $array[$k]);
}
