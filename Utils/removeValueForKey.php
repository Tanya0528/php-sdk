<?php

namespace Utils;

function removeKey(string $key, array $keys, array &$array)
{
    if (!array_key_exists($key, $array)) {
        return false;
    }

    if (!count($keys)) {
        unset($array[$key]);
        return true;
    }

    $nextKey = array_shift($keys);

    $removed = removeKey($nextKey, $keys, $array[$key]);
    if ($removed && !count(array_keys($array[$key]))) {
        unset($array[$key]);
    }

    return $removed;
}

function removeValueForKey(string $key, array &$array) {
    $keys = explode('.', $key);

    $currentKey = array_shift($keys);

    return removeKey($currentKey, $keys, $array);
}
