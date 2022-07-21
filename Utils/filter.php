<?php

namespace Utils;

use function Utils\prune;
use function Utils\expand;

require_once __DIR__ . '/prune.php';
require_once __DIR__ . '/expand.php';

function filter(array $array, array $active)
{
    $pruned = prune($array, $active);
    return expand($pruned);
}
