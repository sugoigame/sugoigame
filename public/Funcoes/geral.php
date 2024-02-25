<?php
function has_chance($val)
{
    $rnd = rand(0, 400) / 4;
    return $rnd <= $val ? true : false;
}

function get_chance()
{
    return rand(0, 400) / 4;
}

function formulaExp($nivel = 1)
{
    return 500 * $nivel;
}

function keys_are_equal($array, $search)
{
    foreach ($search as $key => $value) {
        if ($search[$key] != $array[$key]) {
            return false;
        }
    }
    return true;
}

function array_find($array, $search)
{
    foreach ($array as $item) {
        if (keys_are_equal($item, $search)) {
            return $item;
        }
    }
    return null;
}
