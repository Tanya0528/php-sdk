<?php

namespace Utils;

function setKeyToValue(string $key, $value, array &$array) {
    $keys = explode('.', $key);

    $keys = array_reverse($keys);

    foreach ($keys as $i => $k) {

        $setval = ($i === 0) ? $value : $array;

        $array = [$k => $setval];

    }

    return $value;
}

