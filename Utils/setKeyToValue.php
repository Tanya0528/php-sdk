<?php

namespace Utils;

// function setKeyToValue(string $key, $value, array $array)
// {
//     $keys = explode('.', $key);

//     $keys = array_reverse($keys);

//     foreach ($keys as $i => $k) {

//         $setval = ($i === 0) ? $value : $array;

//         $array = [$k => $setval];

//     }

//     return $array;
// }

// $input = ['native' => ['pdp' => ['page_layout' => 'Layout 1']]];

// $output = setKeyToValue('native.newUser', true, $input);

// print_r($output);

function setKeyToValue(string $key, $value, array &$array) {
    $current = $array;
    $keys = explode('.', $key);

    for ($i = 0; $i < count($keys); $i++) {
        $k = $keys[$i];

        if ($i === (count($keys) - 1)) {
            $current[$k] = $value;

            echo $k . PHP_EOL;
            echo $value . PHP_EOL;
            break;
        }

        if (!isset($current[$k])) {
            $current[$k] = [];
            break;
        }
    
        $current = $current[$k];

    }
    
    print_r($current);

    return $value;
}

$input = [];

$input['native']['newUser'] = true;

print_r($input);