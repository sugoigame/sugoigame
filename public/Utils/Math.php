<?php
namespace Utils;

class Math
{
    public static function min_max($value, $min, $max)
    {
        if ($value < $min) {
            $value = $min;
        }
        if ($value > $max) {
            $value = $max;
        }
        return $value;
    }
}
